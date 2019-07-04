/*
 *
 *
 *  gcc -fno-stack-protector -z execstack  buffer_01.c
 * 
 * Usage normal, nom <10
 * $ ./a.out  bob
 * Bonjour bob
 * 
 * nom>10, mais <20
 * ~$ ./say_hello abcdefghijklmnopqrstuvwxyz
 * qrstuvwxyz abcdefghijklmnopqrstuvwxyz
 * 
 * $ ./a.out  bobaaaaaaaaaaaaaaaaaa
 * aaaaaaaaaaa bobaaaaaaaaaaaaaaaaaa
 * flag{J3_su1s_b13n_tr0p_d3b0rd3}
 *
 * nom > variables+variables systèmes
 * $ ./a.out  bobaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
 * aaaaaaaaaaaaaaaaaaaaaaaaa bobaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
 * flag{J3_su1s_b13n_tr0p_d3b0rd3}
 * Erreur de segmentation (core dumped)
 */



#include <stdio.h>
#include <string.h>


void print_flag();


int main(int argc, char *argv[])
{
    char  tst=0;
    char intro[10]="Hello";
    char name[10];

    // Check name
    if (argc<=1) { 
        printf("Usage %s <user name>\n", argv[0]);
        return 1;
    }
   
    // On copie le nom passé en argument 
    // dans le tableau name qui a une longueur de 10
    strcpy(name, argv[1]);

    // Print Hello bob
    printf("%s %s\n", intro, name);

    // Mdr, tst est toujours égal à 0, t'auras jamais mon flag looser
    if (tst>0) print_flag();

    // Pfff, chuis creuvé, vais faire un Tetris
    return 0;
}