# Creates the mysql user account and grant them privleges on all eatable db tables.
# Create the eatable database before running.

CREATE USER 'eatable'@'localhost' IDENTIFIED BY 'password';
GRANT ALL ON eatable.* TO 'eatable'@'localhost';