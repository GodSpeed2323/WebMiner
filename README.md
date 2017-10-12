# WebMiner

## Qu'est-ce que c'est ?

Nous vous proposons un système de **Leveling** & **Ranking** lié avec une **économie virtuelle** pour votre serveur Discord. Les utilisateurs s'inscrivent sur un site qui leurs permette de Miner une **Crypto-Monnaie** (le Monero), ce qui leurs rapporte des **Points** & à vous de l'**Argents**. Les **Points** sont utilisés pour le **Leveling** & **Ranking**, permettant de récompenser les meilleurs contributeurs. Un **Bot** est connecté sur votre **Serveur Discord** afin d'afficher certaines statistiques de la Mine.

## Pourquoi l'utilisé ?

Si vous souhaitez participer activement au développement du serveur mais que vous n'avez pas d'argent, vous pourrez contribuer de cette manière ! WebMiner peut rapporter **2€/jours** pour un total de 2.000points/s (environ 25 Mineurs), cela peut permettre de proposé des lots conséquents pour les **Mineurs** les plus investie !

**WebMiner est un moyen simple et OpenSource d'aide au développement de votre Serveur Discord.**

## Comment ça Marche ?

Au moment où vous lancez WebMiner, votre machine mine du **Monero**, une cryptomonnaie. Le principe est simple : Votre ordinateur va alors résoudre des calculs, ceux-ci permettent la validation de transaction en Monero et la création d'une petite partie de la crypto-monnaie. L'algorithme utilise principalement le **CPU** & le **Réseau** via une exécution **Javascript** sur le site.
En récompense de leurs efforts, les **Mineurs** gagnent des points, qui peuvent être utilisé sur des **plateformes communautaires** comme **Discord**, ils pourront ainsi évoluer dans le Ranking du serveur.
Un **Classement** des mineurs est disponible sur le **site** & le **bot** (€top), les meilleurs se verront récompensés par des Prix & Levels sur le serveur discord.

#### [Site](http://webminer.slote.me) | [Forum](forum.slote.me/categories/webminer) | [Discord](https://discord.gg/NKSwDgK)

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for production and development purposes.

### Prerequisites

* [CoinHive Account](coinhive.com/) - Mining Pool
* [Composer](https://getcomposer.org/) - Dependency Manager for PHP

### Installing

A step by step series of examples that tell you have to get a production env running

Download & Installation


```
git clone https://github.com/Mediashare/WebMiner
cd WebMiner/
composer install
 # Enter Configuration
php app/console doctrine:database:create
php app/console doctrine:schema:update --force

```


## Contact
* [Discord]
  * Name : L'Escale
  * Username : @Slote
  * Url : https://discord.gg/NKSwDgK

* [Twitter]
  * @Mediashare_Supp
  
* [Irc]
  * Host : irc.slote.me (offline)
  * Port : 6667

* [Mail]
  * admin@slote.me
  * Mediashare.supp@gmail.com
  * Marquand.Thibault@gmail.com
  

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct, and the process for submitting pull requests to us.

### Donate
My BitCoin Address : 1NFoAx12XazvYZH7G8vZfo8ibyFoJiQc3v || Mining with WebMiner : http://WebMiner.slote.me

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/your/project/tags). 

## Authors

* **Thibault Marquand** - *Initial work* - [Mediashare](https://github.com/Mediashare)

See also the list of [contributors](https://github.com/Mediashare/BiTrade/graphs/contributors) who participated in this project.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details
