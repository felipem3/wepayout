## Instalação

```
docker run --rm \
-u "$(id -u):$(id -g)" \
-v $(pwd):/opt \
-w /opt \
laravelsail/php80-composer:latest \
composer install --ignore-platform-reqs
```

`cp ./.env-example .env`

`sail up -d`
### Execute as migrations
`sail artisan migrate`
### Execute a queue
`sail artisan queue:work`
### Execute a schedule
`sail artisan schedule:work`

### Rodando os testes
`sail artisan test`




Crie uma aplicação que faça o gerenciamento de Ordem de Pagamentos.

Um cliente poderá subir informações necessárias para realizar um pagamento e o sistema terá a responsabilidade de determinar para qual banco aquele pagamento será processado

Um pagamento é composto por:
ID: identificador único da WePayOut (conhecido apenas após a criação).
Invoice: Identificador do cliente que deverá ser único para cada cliente.
Nome do beneficiário: String
Código do banco do beneficiário: String, aceitando apenas dígitos
Número da agência do beneficiário: String, aceitando apenas dígitos
Número da conta do beneficiário:  String, aceitando apenas dígitos
Valor do pagamento: Unsigned Double
Status (Inicialmente como CRIADO)
Banco processador (Conhecido apenas quando o pagamento é enviado para processamento)

A aplicação deverá possuir 2 bancos processadores de pagamentos diferentes, ambos com as mesmas funcionalidades.

Os bancos deverão implementar os métodos:
    Registra pagamento
    Consulta pagamento

O pagamento será criado através de um endpoint disponível em uma API REST
    Após criado cada pagamento possuirá um identificador único e sequencial (ID)
   O pagamento deverá ter uma invoice única
   O pagamento não poderá ter valor menor que 0.01 e não poderá ser superior a 100000
   O código do banco não poderá ser vazio ou ter mais que 3 dígitos
   A agência não poderá ser vazia ou ter mais que 4 dígitos
   A conta não poderá ser vazia ou ter mais que 15 dígitos

Após armazenado o pagamento deverá ser enviado para a fila que determinará qual banco será utilizado para realizá-lo.  Nesse momento o pagamento deverá ter seu status alterado de CRIADO para PROCESSANDO
    
Na fila de pagamentos:
	O banco que irá executar o pagamento será escolhido utilizando as seguintes regras:
		Banco 1: Pagamento for de ID ímpar
		Banco 2: Pagamento for de ID par
    Após o processamento o pagamento deverá ter seu status alterado para PROCESSADO.
    E uma chamada para consulta de status no processador agendada para após 2 minutos
    	Após a chamada o pagamento deverá aleatoriamente ser setado para PAGO ou REJEITADO

Você pode ou não utilizar um gerenciador de fila
Você pode ou não usar um banco de dados relacional

É esperado que você implemente os design pattern strategy ou factory durante a implementação.

A API deverá 3 métodos:
Criar um pagamento
Consultar um pagamento utilizando seu ID, retornando todos os dados do pagamento
Listar todos os pagamentos na base de dados

Você deverá entregar um repositório com o código fonte hospedado na plataforma de sua preferência. 

Compartilhe o repositório com o email matheus@wepayout.co, caso esse email não esteja disponível nos avise.

Whatsapp +5541988328311
