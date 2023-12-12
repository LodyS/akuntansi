
dir=.

if [ -d "$dir" ]; then
    branch=$(git --git-dir "$dir/.git" branch | sed -n -e 's/^\* \(.*\)/\1/p')
    status=$(git --git-dir "$dir/.git" --work-tree=$dir status)
else
    branch='.git dir not found'
    status=''
fi

echo
echo "* Folder: $dir/.git"
echo "* Branch: $branch"
echo "* Status:"
echo
echo "$status"
echo

if [ -z "$branch" ]
then
    git clone git@219.83.123.134:akuntansi_stable5.git
else
     git pull origin $branch
fi

composer config --global process-timeout 2000
composer install
composer dump-autoload
cls
clear
php artisan run:update
