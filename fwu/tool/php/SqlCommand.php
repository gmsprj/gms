<?php
namespace FWU;

abstract class SqlCommand
{
    private $longOptions = [];
    private $config = [];
    private $name = "";
    protected $dbName = "";
    protected $dbUser = "";

    abstract public function getCommandLine();

    public function __construct()
    {
        $this->longOptions = [
            "help" => "show usage",
            "db-name:" => "database name",
            "db-user:" => "database user name",
        ];
        $this->config = [
            "ynPrompt" => true,
        ];
        $this->name = $this->getCommandName();

        // TODO: Remove me
        die("Sorry!\nこのツールは現在使えません。fwu/database/ にある SQL ファイルを使ってください。\n\n");
    }

    public function getCommandName()
    {
        return basename(__FILE__, ".php");
    }

    public function getYNPromptMessage()
    {
        return "Are you sure?";
    }

    public function ynPrompt()
    {
        $prmpt = $this->getYNPromptMessage() . " (y/n) < ";

        while (true) {
            printf("%s", $prmpt);

            $in = chop(fgets(STDIN));
            $lower = strtolower($in);
            if ($lower == "y" || $lower == "yes") {
                return true;
            } else if ($lower == "n" || $lower == "no") {
                return false;
            }
        }
    }

    public function usage()
    {
        $maxlen = 0;
        foreach ($this->longOptions as $k => $v) {
            $maxlen = max($maxlen, strlen(rtrim($k, ":")));
        }

        printf("Usage: %s [option]...\n\n", $this->name);

        foreach ($this->longOptions as $k => $v) {
            printf("    --%s %s.\n", str_pad(rtrim($k, ":"), $maxlen), $v);
        }

        printf("\n");
        exit(1);
    }

    public function run()
    {
        $opts = getopt("", array_keys($this->longOptions));
        if (array_key_exists("help", $opts) ||
            (!isset($opts["db-name"]) || !isset($opts["db-user"]))) {
            $this->usage();
        }

        $this->dbName = $opts["db-name"];
        $this->dbUser = $opts["db-user"];
        
        if ($this->config["ynPrompt"] && !$this->ynPrompt()) {
            return;
        }

        $cmd = $this->getCommandLine() . PHP_EOL;

        //var_dump($cmd);
        system($cmd);
    }
};

?>

