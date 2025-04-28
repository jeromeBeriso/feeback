# Use an official PHP image from the Docker Hub
FROM php:7.4-apache

# Install Python and necessary libraries (including pip)
RUN apt-get update && apt-get install -y python3 python3-pip

# Install Python dependencies (you can modify these based on your requirements)
RUN pip3 install googletrans==4.0.0-rc1 textblob

# Copy the local application code into the container
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html/

# Expose port 80 for the web server
EXPOSE 80

# Command to run the application
CMD ["apache2-foreground"]
