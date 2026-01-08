# Digital Sovereignty Quiz - Dockerfile
FROM registry.access.redhat.com/ubi10/php-83:latest

# Metadata
LABEL maintainer="Chris Jenkins <chrisj@redhat.com>" \
      version="2.0.0" \
      description="Viewfinder Digital Sovereignty Quiz"


# Set working directory
WORKDIR /opt/app-root/src

# Install system dependencies and PHP extensions
USER root
RUN dnf install -y \
    httpd \
    php-fpm \
    php-json \
    && dnf clean all \
    && rm -rf /var/cache/dnf

# Configure Apache for security
RUN sed -i 's/^ServerTokens .*/ServerTokens Prod/' /etc/httpd/conf/httpd.conf && \
    sed -i 's/^ServerSignature .*/ServerSignature Off/' /etc/httpd/conf/httpd.conf && \
    echo 'Header always set X-Content-Type-Options "nosniff"' >> /etc/httpd/conf/httpd.conf && \
    echo 'Header always set X-Frame-Options "SAMEORIGIN"' >> /etc/httpd/conf/httpd.conf && \
    echo 'Header always set X-XSS-Protection "1; mode=block"' >> /etc/httpd/conf/httpd.conf


# Copy application files
COPY --chown=1001:0 index.php ./
COPY --chown=1001:0 certificate.php ./
COPY --chown=1001:0 images/ ./images/

# Set proper permissions
RUN chown -R 1001:0 /opt/app-root/src && \
    chmod -R g=u /opt/app-root/src && \
    chmod 755 /opt/app-root/src

# Set proper permissions
USER 1001

# Expose port 8080 (default for UBI PHP images)
EXPOSE 8080

# Start Apache server (default CMD from base image)
CMD ["php", "-S", "0.0.0.0:8080", "-t", "/opt/app-root/src"]
