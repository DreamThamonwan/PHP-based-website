
# Install mysqli + common utilities
RUN docker-php-ext-install mysqli

# Enable Apache rewrite (optional, safe)
RUN a2enmod rewrite

# Optional: set a sensible Apache docroot config
# (If your entry point is index.php in repo root, this is fine as-is.)
WORKDIR /var/www/html
