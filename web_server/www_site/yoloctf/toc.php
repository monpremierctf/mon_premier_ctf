
            <?php
            include ("ctf_challenges.php");

            function print_toc_entry($cat){
                print '<a href="index.php?p='.$cat.'">';
                print "<pre>";
                print ($cat);
                print "</pre>";
                print '</a> ';
            }
            print '<a href="index.php">';
                print "<pre>";
                print "Intro";
                print "</pre>";
                print '</a> ';
            foreach(getCategories() as $cat){
                print_toc_entry($cat);
            }
            print '<a  ><pre> </pre></a> ';
            print '<a href="my_term.php" target="_blank"><pre>[Mon terminal]</pre></a> ';
            print '<a href="scoreboard.php" target="_blank"><pre>[Score board]</pre></a> ';
            print '<a href="index.php?p=Dashboard" target="_blank"><pre>[Dashboard]</pre></a> ';
            ?>
