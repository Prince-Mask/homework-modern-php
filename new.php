<?php

class ExtensibleDecorator {

    /** @var callable[] */
    private $filters = [];

    /** @var string */
    private $pattern;

    public function __construct(string $pattern) {
        $this->pattern = $pattern;
    }

    /**
     * filters must expect a single string parameter and output bool value
     * if any filter function returns true, the value is filtered out from result
     * @param callable $filter
     * @return int The id of registered filter in case you need to unregister
     */
    public function registerFilter(callable $filter) : int {
        $this->filters[] = $filter;
        return array_key_last($this->filters);
    }

    /**
     * @param int $id
     */
    public function unregisterFilter(int $id) {
      unset($this->filters[$id]);
    }

    /**
     * @return \self the object itself for method chaining
     */
    public function resetFilters() : self {
        $this->filters = [];
        return $this;
    }

    /**
     * @param resource $stream
     * @return iterable yields a value for every row of the stream unless 
     * it is filtered out
     * @throws InvalidArgumentException if stream is not a resource / file handle
     */
    public function process($stream) : iterable {
        if (!is_resource($stream)) {
            throw new InvalidArgumentException(
              'Parameter "stream" must be a resource / file handle'
            );
        }

        $matches = array();
        while ($row = fgets($stream)) {
            // decorator: extract log level
            if (preg_match($this->pattern, $row, $matches)) {
                $value = strtolower($matches[1]);

                $passed = true;
                foreach($this->filters as $filter) {
                    if ($filter($value)) {
                        $passed = false;
                        break;
                    }
                }
                if ($passed) {
                    yield $value;
                }
            }
        }
    }
}

$stream = array_key_exists(1, $argv) ? fopen($argv[1], 'r') : STDIN;
$pattern = '/test\.(\w+)/';
$reportFunction = function (array $values) : void {
    arsort($values);
    foreach ($values as $value => $count) {
        echo "$value: $count" . PHP_EOL;
    }
};

$decorator = new ExtensibleDecorator($pattern);
$decorator->registerFilter(
    function(string $value) : bool {
        return $value == 'debug';
    }
);
$stats = array();
$timestampLastReport = microtime(true);
system('tput smcup');
system('tput home');
foreach ($decorator->process($stream) as $value) {
    if (array_key_exists($value, $stats)) {
        $stats[$value]++;
    } else {
        $stats[$value] = 1;
    }

    if (microtime(true) - $timestampLastReport > 1) {
        system('tput rmcup');
        system('tput smcup');
        system('tput home');
        $reportFunction($stats);
        $timestampLastReport = microtime(true);
    }
}
system('tput rmcup');
$reportFunction($stats);
