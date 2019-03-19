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
 * $ ./a.out  bobaaaaaaaaa
 * aa bobaaaaaaaaa 
 * 
 * $ ./a.out  bobaaaaaaaaaaaaaaaaaa
 * aaaaaaaaaaa bobaaaaaaaaaaaaaaaaaa
 * flag{J3_su1s_tr0p_d3b0rd3}
 *
 * nom > variables+variables systèmes
 * $ ./a.out  bobaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
 * aaaaaaaaaaaaaaaaaaaaaaaaa bobaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa
 * flag{J3_su1s_tr0p_d3b0rd3}
 * Erreur de segmentation (core dumped)
 */



#include <stdio.h>
#include <string.h>

int main(int argc, char *argv[])
{
    int  tst=0;
    char intro[10]="Bonjour";
    char name[10];

    if (argc<=1) { 
        printf("Usage %s <user name>\n", argv[0]);
    }
   
    strcpy(name, argv[1]);
    printf("%s %s\n", intro, name);
    if (tst>0) printf("flag{J3_su1s_tr0p_d3b0rd3}\n");
    return 0;
}