//
//
//
// gcc dechiffre_01.c -o dechiffre_01


#include <stdio.h>

int main(int argc, char *argv[])
{
    char buf[60];
    if (argc!=3){
        printf("Usage %s [fichier à dechiffrer] [fichier de sortie]\n", argv[0]);
        return 1;
    }

    FILE  *fr= fopen(argv[1], "rb");
    if (!fr) {
        printf("Erreur: Impossible de lire %s\n", argv[1]);
        return 1;
    }

    FILE *fw= fopen(argv[2], "wb+");
    if (!fw) {
        printf("Erreur: Impossible d'écrire sur %s\n", argv[1]);
        fclose(fr);
        return 1;
    }


    while (fread(buf, 1, 1, fr)){
        buf[0] ^= 0x71;
        fwrite(buf, 1, 1, fw);
    }
    fclose(fr);
    fclose(fw);
    return 0;
}