# fwu/

* ./database/ ... データベース関連のファイル。tool/ からも参照される。
* ./tool/ ... 開発ツール（UNIX 系 であれば ./tool/unix, Windows であれば ./tool/windows）。

## tool/php/

PHP用ツール。ファイル名の接頭辞は 'fwu-' 。
各スクリプトはコマンド・ラインのオプションで設定後、実行される。

    $ # オプションで設定された DB を初期化。
    $ php fwu-initdb --db-name=your_database --db-user=your_name

## tool/unix/

UNIX用ツール。ファイル名の接頭辞は 'fwu-' 。スクリプトの多くは環境変数を使用する。環境変数の接頭辞は 'FWU_' 。

環境変数:

    FWU_DBUSER ... DB のユーザー名。
    FWU_DBNAME ... DB 名。

例えばシェル（bash）上で以下のコマンドを実行すると、各スクリプトは
設定された DB ユーザー、DB 名に対して処理を行う。

    $ # DB を設定。
    $ export FWU_DBUSER=your_name
    $ export FWU_DBNAME=your_database

    $ # 設定中の DB を見る。
    $ echo $FWU_DBUSER
    $ echo $FWU_DBNAME
    
    $ # 設定された DB を初期化（パスにツールが通っている場合）。
    $ fwu-initdb

