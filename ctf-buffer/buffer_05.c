/*
 *
 *
 *  gcc -m32 -g -fno-stack-protector -z execstack  buffer_05.c -o buffer_05
 *  echo 0 > /proc/sys/kernel/randomize_va_space
 */



#include <stdio.h>
#include <string.h>



void print_flag();


void say_hello(char *arg)
{
    char buffer[200];
    strcpy(buffer,arg);
    printf("Hello %s\n", buffer);
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