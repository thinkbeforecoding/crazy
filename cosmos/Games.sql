SELECT c.p,e.c, ARRAY(SELECT p.Name FROM p IN e.d.Players) Players FROM c
JOIN e IN c.e
WHERE STARTSWITH(c.p,'game-') 
    AND (NOT (STARTSWITH(c.p,'game-cmd-')))  
    AND (e.c = 'Started' OR e.c = 'GameWon') 
ORDER BY c._ts DESC