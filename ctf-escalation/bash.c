#include <stdio.h>
#include <stdlib.h>
#include <sys/types.h>
#include <unistd.h>

int main()
{
   setuid( 1001 );
   system( "/bin/sh" );
   return 0;
}
