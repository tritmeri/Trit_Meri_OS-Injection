# Base image: Apache + PHP
FROM php:8.1-apache

# Install utilities (use concrete netcat package)
RUN apt-get update && apt-get install -y --no-install-recommends \
    iputils-ping netcat-openbsd gcc make \
    && rm -rf /var/lib/apt/lists/*

# Create webroot
COPY src/ /var/www/html/

# Add the user flag (web visible)
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Compile SUID escalation binary (we will set SUID)
COPY suid_root.c /tmp/suid_root.c
RUN gcc -o /usr/local/bin/suid_root /tmp/suid_root.c \
    && chown root:root /usr/local/bin/suid_root \
    && chmod 4755 /usr/local/bin/suid_root \
    && rm /tmp/suid_root.c

# Add root flag file
COPY root_flag.txt /root/root_flag.txt
RUN chmod 600 /root/root_flag.txt

EXPOSE 80
CMD ["apache2-foreground"]
