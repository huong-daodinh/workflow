echo "Enter sudo password: " -r password
echo $password | sudo systemctl stop mysql
echo $password | sudo systemctl stop apache2
./vendor/bin/sail up -d --remove-orphans
