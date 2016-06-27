# gm/

* ./db/ ... データベース関連のファイル。tool/ からも参照される。
* ./tool/ ... 開発ツール（UNIX 系 であれば ./tool/unix, Windows であれば ./tool/windows）。
* ./doc/ ... 開発用ドキュメント。

## tool/unix/

UNIX用ツール。ファイル名の接頭辞は 'gm-' 。スクリプトの多くは環境変数を使用する。環境変数の接頭辞は 'GM_' 。

環境変数:

    GM_DBUSER ... DB のユーザー名。
    GM_DBNAME ... DB 名。

例えばシェル（bash）上で以下のコマンドを実行すると、各スクリプトは
設定された DB ユーザー、DB 名に対して処理を行う。

    $ # DB を設定。
    $ export GM_DBUSER=your_name
    $ export GM_DBNAME=your_database

    $ # 設定中の DB を見る。
    $ echo $GM_DBUSER
    $ echo $GM_DBNAME
    
    $ # 設定された DB を初期化（パスにツールが通っている場合）。
    $ gm-initdb

