
dir=.

if [ -d "$dir" ]; then
    branch=$(git --git-dir "$dir/.git" branch | sed -n -e 's/^\* \(.*\)/\1/p')
    status=$(git --git-dir "$dir/.git" --work-tree=$dir status)
else
    branch='.git dir not found'
    status=''
fi

echo
echo "* Branch: $branch"
echo "* Status:"
echo
echo "$status"
echo

if [ -z "$branch" ]
then
    echo "* branch: not found"
else
    git pull origin $branch
    php artisan migrate
fi
