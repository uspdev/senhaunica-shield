# senhaunica
Biblioteca genérica para integrar senha única em PHP

# Uso

Deve-se criar uma rota (/loginusp por exemplo) onde vai se chamar:
```
$senhaunica = new Uspdev/Senhaunica('consumer_key','consumer_secret','callback_id',$amb); //$amb = 1; //teste; $amb = 2 //producao
if ($login = $senhaunica->login()) {
    header('Location:/alguma_rota');
}
```
