# Teste de symfony
realizar o download com git clone https://github.com/dddcastro/symfony.git
composer update

alterar o .env com as informações de conexão mysql
DATABASE_URL="mysql://NOME_DO_USUARIO:SENHA_DO_USUARIO@localhost:3306/NOME_DO_BANCO?serverVersion=8.0.32&charset=utf8mb4"

# Tomar cuidado pois caso vc tenha dados salvos no banco eles serão apagados
symfony console doctrine:database:drop --force
symfony console doctrine:database:create

symfony console make:migration
symfony console doctrine:migrations:migrate

#a partir daqui o sistema ja esta operante podemos usar o 
symfony serve --port=8080
#para poder acessar o sistema pelo endereço http://localhost:8080/
#tem uma navbar com os principais caminhos
# recomendo começar por Hospitais, Beneficiario, Medico, Consulta, Observação
# você não conseguira cadastrar uma consulta se não cadastrar Medicos, Beneficiario, Hospitais previamente.

#eu não consegui criar o metodo de editar observação usando PUT. Quando tento enviar o formData, ele chega como NULL. apenas consigo enviar arrays simples.
