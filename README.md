### install libraries

# composer install
# npm install

### install passport

# php artisan migrate
# php artisan passport:install
# php artisan passport:keys (usar al regenerar claves)
# php artisan key:generate (regenera app key de laravel)

### create user and company

# php artisan tinker

# $company = new Company();
# $company->name = '';
# $company->save();
# echo $company->id;

# $user = new User();
# $user->name = '';
# $user->password = Hash::make('password');
# $user->email = '';
# $user->company_id = '';
# $user->save();

# $user2 = new User();
# $user2->name = 'Demo';
# $user2->password = Hash::make('demodemo');
# $user2->email = 'demo@gmail.com';
# $user2->company_id = '';
# $user2->save();
