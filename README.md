# Symfony XML Generator (sf_data_export)

The purpose of this application is to read source site database, currently WordPress, 
and export that data in XML or JSON format.

## How to use
- Fork or Clone the repo.
- Install the dependencies using 'composer install'.
- Create the config.local.php with connection details to your WordPress site. Example,
```
  $connection['mysql'] = [
    'host' => '127.0.0.1',
    'port' => '3306',
    'db' => 'wordpress',
    'user' => 'dbuser',
    'password' => 'dbpwd',
  ];
```
- Run the following command from command line to export the data,
```
  php bin/app.php sf_data_export
```
