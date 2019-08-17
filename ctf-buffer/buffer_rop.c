#include <stdio.h>
#include <stdlib.h>
#include <string.h>

// Pour la compilation, il faut ajouter ces informations pour avoir les bonnes protections
// clang -o rop rop.c -m32 -fno-stack-protector  -Wl,-z,relro,-z,now,-z,noexecstack -static

// sudo apt-get install -y gcc gcc-multilib  : compil
// sudo apt install clang
// sudo dpkg --add-architecture i386  ? execution
// sudo apt-get install libc++1:i386  ? execution

int main(int argc, char ** argv) {
    char buff[128];

    gets(buff);

    char *password = "I am h4cknd0";

    if (strcmp(buff, password)) {
        printf("You password is incorrect\n");
    } else {
        printf("Access GRANTED !\n");
    }

    return 0;
}
