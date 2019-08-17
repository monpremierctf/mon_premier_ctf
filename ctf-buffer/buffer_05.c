/*
 *
 *
 *  gcc -m32 -g -fno-stack-protector -z execstack  buffer_05.c -o buffer_05
 *  echo 0 > /proc/sys/kernel/randomize_va_space
 */



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

void say_hello(char *arg)
{
    char buffer[200];
    printf("Hello %s\n", arg);
    strcpy(buffer,arg);    
}

int main(int argc, char *argv[])
{
    if (argc<=1) { 
        printf("Usage %s <user name>\n", argv[0]);
        return 1;
    }

    say_hello(argv[1]);

    return 0;
}