<?php

const TOLERANCE = 2;
class SetTree {


    static function countAux($s, $acc)
    { 
        switch(get_class($s))
        {
            case 'SetNode':
                return SetTree::countAux($s->left, SetTree::countAux($s->right, $acc+1));
            case 'SetOne':
                return $acc+1;
            default:
                return $acc;

        }
    }

    static function count($s) 
    {
        return SetTree::countAux($s, 0);
    }

    static function height($t)
    { 
        switch(get_class($t))
        {
            case 'SetEmpty':
                return 0;
            case 'SetOne':
                return 1;
            default:
                return $t->height;
        }
    }


    static function mk($l,$k,$r) 
    {
        if ($l instanceof SetEmpty && $r instanceof SetEmpty)
        {
            return new SetOne($k);
        }
        else
        {
            $hl = SetTree::height($l); 
            $hr = SetTree::height($r); 
            $m = $hl < $hr ? $hr : $hl;
            return new SetNode ($k, $l, $r, $m+1);
        }
    }

    static function rebalance($t1, $k,$t2) 
    {
    $t1h = SetTree::height($t1); 
    $t2h = SetTree::height($t2); 
    if  ($t2h > $t1h + TOLERANCE) // right is heavier than left 
        if ($t2 instanceof SetNode)
        {
            $t2k = $t2->value;
            $t2l = $t2->left;
            $t2r = $t2->right; 
            // one of the nodes must have height > height t1 + 1 
            if (SetTree::height($t2l) > $t1h + 1)  // balance left: combination 
            {
                if ($t2l instanceof SetNode)
                {
                    $t2lk=$t2l->value;
                    $t2ll=$t2l->left;
                    $t2lr=$t2l->right;
                    return SetTree::mk(SetTree::mk($t1,$k,$t2ll),$t2lk,(SetTree::mk($t2lr,$t2k,$t2r))); 
                }
                else
                {
                    throw new Exception("rebalance");
                }
            }
            else // rotate left 
            {
                return SetTree::mk(SetTree::mk($t1, $k, $t2l), $t2k, $t2r);
            }
    
        }
        else
        { 
           throw new Exception("rebalance");
        }
    else
        if ($t1h > $t2h + TOLERANCE)  // left is heavier than right 
            if ($t1 instanceof SetNode)
            {
                $t1k = $t1->value;
                $t1l = $t1->left;
                $t1r = $t1->right;
                // one of the nodes must have height > height t2 + 1 
                if (SetTree::height($t1r) > $t2h + 1)
                    // balance right: combination 
                    if($t1r instanceof SetNode)
                    {
                        $t1rk=$t1r->value;
                        $t1rl=$t1r->left;
                        $t1rr=$t1r->right;
                        return SetTree::mk(SetTree::mk($t1l, $t1k, $t1rl), $t1rk, SetTree::mk($t1rr, $k, $t2));
                    }
                    else
                    {
                        throw new Exception("rebalance");
                    }
                else
                    return SetTree::mk($t1l, $t1k, SetTree::mk($t1r, $k, $t2));
            }
            else{
                throw new Exception("rebalance");
            }
        else 
        {
            return SetTree::mk($t1, $k, $t2);
        }
    }


    static function add($comparer, $k, $t)
    { 
        switch(get_class($t))
        { case 'SetNode':
            $k2 = $t->value;
            $l = $t->left;
            $r = $t->right;
            $c = $comparer['Compare']($k, $k2); 
            if   ($c < 0)
                { return SetTree::rebalance(SetTree::add($comparer,$k,$l), $k2, $r); }
            elseif ($c == 0) 
                { return $t; }
            else 
                { return SetTree::rebalance($l, $k2, SetTree::add($comparer, $k, $r));}
        case 'SetOne': 
            $k2 = $t->value;
            // nb. no check for rebalance needed for small trees, also be sure to reuse node already allocated 
            $c = $comparer['Compare']($k, $k2); 
            if ($c < 0) 
                { return new SetNode($k, new SetEmpty(), $t, 2); }
            elseif ($c == 0) 
                { return $t; }
            else 
                { return new SetNode ($k, $t, new SetEmpty(), 2); }
        default:
            return new SetOne($k);
        }
    }

    static function balance($comparer, $t1, $k, $t2)
    {
        // Given t1 < k < t2 where t1 and t2 are "balanced", 
        // return a balanced tree for <t1, k, t2>.
        // Recall: balance means subtrees heights differ by at most "TOLERANCE"
        if ($t1 instanceof SetEmpty)
            return SetTree::add($comparer, $k, $t2); // drop t1 = empty 
        if ($t2 instanceof SetEmpty)
            return SetTree::add($comparer, $k, $t1); // drop t2 = empty 
        if ($t1 instanceof SetOne)
            return SetTree::add($comparer, $k, SetTree::add($comparer, $t1->value, $t2));
        if ($t2 instanceof SetOne)
            return SetTree::add($comparer, $k, SetTree::add($comparer, $t2->value, $t1));
        $k1 = $t1->value;
        $t11 = $t1->left;
        $t12 = $t1->right;
        $h1 = $t1->height;
        $k2 = $t2->value;
        $t21 = $t2->left;
        $t22 = $t2->right;
        $h2 = $t2->height;
        // Have:  (t11 < k1 < t12) < k < (t21 < k2 < t22)
        // Either (a) h1, h2 differ by at most 2 - no rebalance needed.
        //        (b) h1 too small, i.e. h1+2 < h2
        //        (c) h2 too small, i.e. h2+2 < h1 
        if ($h1+TOLERANCE < $h2)
            // case: b, h1 too small 
            // push t1 into low side of t2, may increase height by 1 so rebalance 
            return SetTree::rebalance(SetTree::balance($comparer, $t1, $k, $t21), $k2, $t22);
        elseif ($h2+TOLERANCE < $h1)
            // case: c, h2 too small 
            // push t2 into high side of t1, may increase height by 1 so rebalance 
            return SetTree::rebalance($t11, $k1, SetTree::balance($comparer, $t12, $k, $t2));
        else
            // case: a, h1 and h2 meet balance requirement 
            return SetTree::mk($t1, $k, $t2);
    }

    static function split($comparer, $pivot, $t)
    {
        // Given a pivot and a set t
        // Return { x in t s.t. x < pivot }, pivot in t?, { x in t s.t. x > pivot } 
        switch(get_class($t))
        {
        case 'SetNode':
            $k1 = $t->value;
            $t11 = $t->left;
            $t12 = $t->right;
            $c = $comparer['Compare']($pivot, $k1);
            if  ($c < 0) // pivot t1
            { 
                [$t11Lo, $havePivot, $t11Hi] = SetTree::split($comparer, $pivot, $t11);
                return [$t11Lo, $havePivot, SetTree::balance($comparer, $t11Hi, $k1, $t12)];
            }
            elseif ($c == 0) // pivot is k1 
                return [$t11, true, $t12];
            else            // pivot t2 
            {
                [$t12Lo, $havePivot, $t12Hi] = SetTree::split($comparer, $pivot, $t12);
                return [ SetTree::balance($comparer, $t11, $k1, $t12Lo), $havePivot, $t12Hi];
            }
        case 'SetOne': 
            $k1 = $t->value;
            $c = $comparer['Compare']($k1, $pivot);
            if  ($c < 0) 
                return [$t, false, new SetEmpty()]; // singleton under pivot 
            elseif ($c == 0) 
                return [new SetEmpty(), true, new SetEmpty()]; // singleton is    pivot 
            else 
                return [new SetEmpty(), false, $t];        // singleton over  pivot 
        default:
            return [new SetEmpty(), false, new SetEmpty()];
        }
    }

    static function spliceOutSuccessor($t)
    { 
        switch(get_class($t))
        {
            case 'SetEmpty':
                throw new Exception("internal error: Set.spliceOutSuccessor");
            case 'SetOne':
                return [$t->value, new SetEmpty()];
            default:
                if ($t->left instanceof SetEmpty)
                    return [$t->value, $t->right];
                [$k3, $ll] = SetTree::spliceOutSuccessor($t->left);
                return [$k3, SetTree::mk($ll, $t->value, $t->right)];
        }
    }


    static function remove($comparer, $k, $t)
    { 
        switch(get_class($t))
        {
            case 'SetEmpty':
                return $t;
            case 'SetOne':
                $c = $comparer['Compare']($k, $t->value); 
                if ($c == 0)
                    return new SetEmpty();
                else 
                    return $t;
            default:
            $k2 = $t->value;
            $l = $t->left;
            $r = $t->right;
            $c = $comparer['Compare']($k, $k2);
            if ($c < 0) 
                return SetTree::rebalance(SetTree::remove($comparer, $k, $l), $k2, $r);
            elseif ($c == 0)
            {
                if ($l instanceof SetEmpty)
                    return $r;
                if ($r instanceof SetEmpty)
                    return $l;
                    [$sk, $rr] = SetTree::spliceOutSuccessor($r); 
                    return SetTree::mk($l, $sk, $rr);
            }
            else 
                return SetTree::rebalance($l, $k2, SetTree::remove($comparer, $k, $r));
        }
    }

    static function mem($comparer,$k, $t)
    { 
        switch (get_class($t))
        {
            case 'SetNode':
                $k2 = $t->value;
                $l = $t->left;
                $r = $t->right;
                $c = $comparer['Compare']($k, $k2); 
                if ($c < 0) 
                    return SetTree::mem($comparer, $k, $l);
                elseif ($c == 0) 
                    return true;
                else 
                    return SetTree::mem($comparer, $k, $r);
            case 'SetOne':
                return $comparer['Compare']($k, $t->value) == 0;
            default:
                return false;
        }
    }

    static function union($comparer, $t1, $t2)
    {
        // Perf: tried bruteForce for low heights, but nothing significant 
        if ($t1 instanceof SetNode && $t2 instanceof SetNode)
        {
            $k1 = $t1->value;
            $t11 = $t1->left;
            $t12 = $t1->right;
            $h1 = $t1->height;
            $k2 = $t2->value;
            $t21 = $t2->left;
            $t22 = $t2->right;
            $h2 = $t2->height; // (t11 < k < t12) AND (t21 < k2 < t22) 
            // Divide and Conquer:
            //   Suppose t1 is largest.
            //   Split t2 using pivot k1 into lo and hi.
            //   Union disjoint subproblems and then combine. 
            if ($h1 > $h2){
                [$lo, $_, $hi] = SetTree::split($comparer, $k1, $t2);
                return SetTree::balance($comparer, SetTree::union($comparer, $t11, $lo), $k1, SetTree::union($comparer, $t12, $hi));
            }
            else
            {
                [$lo, $_, $hi] = SetTree::split($comparer, $k2, $t1);
                return SetTree::balance($comparer, SetTree::union($comparer, $t21, $lo), $k2, SetTree::union($comparer, $t22, $hi));
            }
        }
        if ($t1 instanceof SetEmpty)
            return $t2;
        if ($t2 instanceof SetEmpty)
            return $t1;
        if ($t1 instanceof SetOne)
            return SetTree::add($comparer, $t1->value, $t2);
        
        return SetTree::add($comparer, $t2->value, $t1);
    }
    
    
    static function diffAux($comparer, $m, $acc)
    { 
        if ($acc instanceof SetEmpty)
            return $acc;
        switch(get_class($m))
        {
            case 'SetNode':
                return SetTree::diffAux($comparer, $m->left, SetTree::diffAux($comparer, $m->right, SetTree::remove($comparer, $m->value, $acc)));
            case 'SetOne':
                return SetTree::remove($comparer, $m->value, $acc);
            default:
                return $acc;
        }
    }

    static function diff($comparer, $a, $b) 
    {
        return SetTree::diffAux($comparer, $b, $a);
    }

    static function intersectionAux($comparer, $b, $m, $acc)
    { 
        switch( get_class($m))
        {
            case 'SetNode':
                $k = $m->value;
                $l = $m->left;
                $r = $m->right;
                $acc = SetTree::intersectionAux($comparer, $b, $r, $acc);
                $acc = SetTree::mem($comparer, $k, $b) ? SetTree::add($comparer, $k, $acc) : $acc;
                return SetTree::intersectionAux($comparer, $b, $l, $acc);
            case 'SetOne': 
                $k = $m->value; 
                if (SetTree::mem($comparer, $k, $b))
                    return SetTree::add($comparer, $k, $acc);
                else 
                    return $acc;
            default:
                return $acc;
        }
    }

    static function intersection($comparer, $a, $b) 
    {
        return SetTree::intersectionAux($comparer, $b, $a, new SetEmpty());
    }

    static function forall($f, $m)
    {
        switch (get_class($m))
        {
            case 'SetNode':
                return $f($m->value) && SetTree::forall($f, $m->left) && SetTree::forall($f, $m->right);
            case 'SetOne': 
                return $f($m->value);
            default:
                return true;
        }
    }

    static function subset($comparer, $a, $b)
    {
        return SetTree::forall(function ($x) use($comparer, $b) { return SetTree::mem($comparer, $x, $b); }, $a);
    }

    static function minimumElementAux($s, $n)
    {
        switch(get_class($s))
        {
            case 'SetNode':
                return SetTree::minimumElementAux($s->left, $s->value);
            case 'SetOne':
                return $s->value;
            default:
                return $n;
        } 
    }
    
    static function minimumElementOpt($s)
    {
        switch(get_class($s))
        {
            case 'SetNode':
                return SetTree::minimumElementAux($s->left, $s->value);
            case 'SetOne':
                return $s->value;
            default:
                return NULL;
        } 
    }

    static function minimumElement($s)
    {
        $result = SetTree::minimumElementOpt($s);
        if (is_null($result))
            throw new Exception("Set contains no elements");
        return $result;
    }

    static function compareStacks($comparer,$l1,$l2) 
    {
        if (!($l1 instanceof Cons) && !($l2 instanceof Cons))
            return 0;
        if (!($l1 instanceof Cons))
            return -1;
        if (!($l2 instanceof Cons))
            return 1;

        if ($l1->value instanceof SetEmpty && $l2->value instanceof SetEmpty)
            return SetTree::compareStacks($comparer, $l1->next, $l2->next);
        if ($l1->value instanceof SetOne && $l2->value instanceof SetOne)
        {
            $c = Util::compare($l1->value->value, $l2->value->value);
            if ($c != 0) 
                return $c;
            else
                return SetTree::compareStacks($comparer, $l1->next, $l2->next);
        }
        if ($l1->value instanceof SetOne && $l2->value instanceof SetNode && $l2->value->left instanceof SetEmpty)
        {
            $c = Util::compare($l1->value->value, $l2->value->value);
            if ($c != 0)
                return $c;
            else
                return SetTree::compareStacks($comparer, new Cons(new SetEmpty(), $l1->next), new Cons($l2->value->right, $l2->next));
        }
        if ($l1->value instanceof SetNode && $l1->value->left instanceof SetEmpty && $l2->value instanceof SetOne)
        {
            $c = Util::compare($l1->value->value, $l2->value->value);
            if ($c != 0)
                return $c;
            else
                return SetTree::compareStacks($comparer, new Cons($l1->value->right, $l1->next), new Cons(new SetEmpty(), $l2->next));
        }
        if ($l1->value instanceof SetNode && $l1->value->left instanceof SetEmpty && $l2->value instanceof SetNode && $l2->value->left instanceof SetEmpty)
        {
            $c = Util::compare($l1->value->value, $l2->value->value);
            if ($c != 0)
                return $c;
            else
                return SetTree::compareStacks($comparer, new Cons($l1->value->right, $l1->next), new Cons($l2->value->right, $l2->next));
        }
        if ($l1->value instanceof SetOne)
            return SetTree::compareStacks($comparer, new Cons(new SetEmpty(), new Cons (new SetOne($l1->value->value), $l1->next)), $l2);
        if ($l1->value instanceof SetNode)
            return SetTree::compareStacks($comparer, new Cons($l1->value->left, new Cons (new SetNode($l1->value->value, new SetEmpty(), $l1->value->right, 0), $l1->next)), $l2);
        if ($l2->value instanceof SetOne)
            return SetTree::compareStacks($comparer, $l1, new Cons(new SetEmpty(), new Cons (new SetOne($l2->value->value), $l2->next)));
        
        return SetTree::compareStacks($comparer, $l1, new Cons($l2->value->left, new Cons (new SetNode($l2->value->value, new SetEmpty(), $l2->value->right, 0), $l2->next)));
    }

    static function compare($comparer, $s1, $s2)  
    {
        if ($s1 instanceof SetEmpty)
                return $s2 instanceof SetEmpty ? 0 : -1;
        if ($s2 instanceof SetEmpty)
            return 1;
        
        return SetTree::compareStacks($comparer, new Cons($s1, FSharpList::get_Nil()), new Cons($s2, FSharpList::get_Nil()) );
    }

}

class SetEmpty extends SetTree { }
class SetNode extends SetTree {
    public $value;
    public $left;
    public $right;
    public $height;

    function __construct($value, $left, $right, $height)
    {
        $this->value = $value;
        $this->left = $left;
        $this->right = $right;
        $this->height = $height;
    }
}

class SetOne extends SetTree {
    public $value;
    function __construct($value)
    {
        $this->value = $value;
    }
}


class Set implements IteratorAggregate, iComparable {
    public $Comparer;
    public $Tree;

    function __construct($comparer, $tree)
    {
        $this->Comparer = $comparer;
        $this->Tree = $tree;
    }
    
    function __debugInfo() {
        return [$this->Tree];
    }

    static function FSharpSet___op_Addition($set1, $set2)
    {
        if ($set2->Tree instanceof SetEmpty)
            return $set1; // (* A U 0 = A *)
        if ($set1->Tree instanceof SetEmpty)
            return $set2; //  (* 0 U B = B *)
        return new Set($set1->Comparer, SetTree::union($set1->Comparer, $set1->Tree, $set2->Tree));

    }

    static function FSharpSet___op_Subtraction($set1, $set2)
    {
        if ($set1->Tree instanceof SetEmpty)
            return $set1; //(* 0 - B = 0 *)
        if ($set2->Tree instanceof SetEmpty)
            return $set1; //(* A - 0 = A *)
        return new Set($set1->Comparer, SetTree::diff($set1->Comparer, $set1->Tree, $set2->Tree));
    }

    static function intersect($a, $b)
    {
        if ($b->Tree instanceof SetEmpty)
            return $b; //  (* A INTER 0 = 0 *)
        if ($a->Tree instanceof SetEmpty) 
            return $a; // (* 0 INTER B = 0 *)
        return new Set($a->Comparer, SetTree::intersection($a->Comparer, $a->Tree, $b->Tree));
    }

    static function remove($item,$table)
    {
        return new Set($table->Comparer, SetTree::remove($table->Comparer,$item, $table->Tree));
    }


    static function contains($value, $s)
    {
        return SetTree::mem($s->Comparer, $value, $s->Tree);
    }

    static function ofSeq($seq, $comparer=NULL)
    {
        $tree = new SetEmpty();
        if (is_null($comparer))
            $comparer = [ 'Compare' => 'Util::comparePrimitives'];

        foreach($seq as $item)
        {
            $tree = SetTree::add($comparer, $item, $tree);
        }

        return new Set($comparer, $tree);
    }

    static function empty($comparer)
    {
        return new Set($comparer, new SetEmpty());
    }

    static function isEmpty($set)
    {
        return $set->Tree instanceof SetEmpty;
    }

    static function count($set)
    {
        return SetTree::count($set->Tree);
    }

    static function toList($set)
    {
        return FSharpList::ofSeq($set);
    }

    static function toArray($set)
    {
        return iterator_to_array($set);
    }

    static function union($x,$y)
    {
        return new Set($x->Comparer, SetTree::union($x->Comparer, $x->Tree, $y->Tree));
    }

    static function unionMany($sets)
    {
        $comparer = [ 'Compare' => 'Util::comparePrimitives'];

        return Seq::fold(function($acc,$s) { return Set::union($acc,$s); }, Set::empty($comparer), $sets);
    }

    static function isSubset($set1, $set2) 
    {
        return SetTree::subset($set1->Comparer, $set1->Tree, $set2->Tree); 
    }

    static function minElement($set)
    {
        return SetTree::minimumElement($set->Tree);
    }

    public function getIterator() {
        $stack = [];
        $tree = $this->Tree;
        while(!is_null($tree))
        {
            switch(get_class($tree))
            {
                case 'SetOne':
                    yield $tree->value;
                    $tree = array_pop($stack);
                break;
                case 'SetNode':
                    array_push($stack, $tree->right);
                    array_push($stack, new SetOne($tree->value));
                    $tree = $tree->left;
                break;
                default:
                    $tree = array_pop($stack);
            break;
            }
        }
    } 
    
    public function CompareTo($Other)
    {
        return SetTree::compare($this->Comparer, $this->Tree, $Other->Tree);
    }

}