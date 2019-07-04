
            <?php
            include ("ctf_challenges.php");

            function print_toc_entry($cat){
                print '<a href="index.php?p='.$cat.'">';
                print "<pre class='ctf-menu-color'>";
                print ($cat);
                print "</pre>";
                print '</a> ';
            }

            print '<a href="index.php">';
                print "<pre class='ctf-menu-color'>";
                print "Intro";
                print "</pre>";
                print '</a> ';
            print '<a  ><pre> </pre></a> ';
            foreach(getCategories() as $cat){
                print_toc_entry($cat);
            }
            print '<a  ><pre> </pre></a> ';
            print '<a href="index.php?p=Xterm"><pre class="ctf-menu-color">[Mon terminal]</pre></a> ';
            print '<a href="scoreboard.php" target="_blank"><pre class="ctf-menu-color">[Score board]</pre></a> ';
            print '<a href="feedback.php" "><pre class="ctf-menu-color">[Feedback]</pre></a> ';



            if (isset($_SESSION['login'] )) {
                if (($_SESSION['login']=='admin' )) {
                    print '<a href="zen.php" ><pre class="ctf-menu-color">[Admin]</pre></a> ';
                }
            }
            ?>
