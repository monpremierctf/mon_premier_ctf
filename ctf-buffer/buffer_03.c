/*
 *
 *
 *  gcc -fno-stack-protector -z execstack  buffer_01.c
 * 
 * Usage normal, nom <10
 * $ ./a.out  bob
 * Bonjour bob
 */



#include <stdio.h>
#include <string.h>


void print_flag();


int main(int argc, char *argv[])
{
    char  tst[16]="No passaran";
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

    printf("\n====== DEBUG INFO START ========\n");
    printf("name[16] =%s\n",name);
    printf("intro[10]=%s\n",intro);
    printf("tst[10]  =%s\n",tst);    
    printf("====== DEBUG INFO STOP  ========\n\n");


    // Print Hello bob
    printf("%s %s\n", intro, name);

    // Mdr, tst est toujours égal à 0, t'auras jamais mon flag looser
    if (strncmp(tst, "SecCheck", 8)==0) print_flag();

    // Pfff, chuis creuvé, vais faire un Tetris
    printf("\n\n");
    return 0;
}