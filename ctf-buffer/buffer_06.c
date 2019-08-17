#include <stdio.h>
#include <string.h>
#include <stdlib.h>  // system


void print_flag();

void jmp_esp()
{
    __asm__("jmp *%esp");
}

void fct_system()
{
    system("id");
}

int main(int argc, char *argv[])
{
    char buf[100];

    gets(buf);
    printf(buf);

    return 0;
}