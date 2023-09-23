oc-salesforce

Création d'une clef pour sales force : https://mannharleen.github.io/2020-03-03-salesforce-jwt/
$ openssl genrsa -out privatekey.pem 1024
$ openssl req -new -x509 -key privatekey.pem -out publickey.cer -days 3650

Problème depuis supression du fork : Omniphx\Forrest\Providers\Laravel\LaravelSession
use Illuminate\Config\Repository as Config;  use Winter\Storm\Config\Repository as Config; ou mieux Illuminate\Contracts\Config\Repository;