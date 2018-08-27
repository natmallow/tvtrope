<?php

class segment {

    //segments array
    private $segments = [];
    public $statusAppend = '';

    /**
     * 
     * @return type
     */
    public function getSegments() {
        return $this->segments;
    }

    /**
     * 
     * @param type $gameArr
     * @return $this
     */
    public function generate($gameArr) {

        foreach ($gameArr as $value) {
            $this->setSegments($value);
        }

        return $this;
    }

    /**
     * 
     * @param type $array
     * @return boolean|$this
     */
    public function setSegments($array) {

        if (2 > count($array)) {
            echo "There are no adjacent points set for the vertex {$array[0]}";
            return FALSE;
        }

        $vertex = $array[0];
        array_shift($array);

        foreach ($array as $value) {
            $this->addSegment($vertex, $value);
        }

        return $this;
    }

    /**
     * 
     * @param type $searchkey
     * @return type
     */
    private function hasSegment($searchkey) {
        return in_array($searchkey, $this->segments) || in_array(strrev($searchkey), $this->segments);
    }

    /**
     * 
     * @param type $primary
     * @param type $secondary
     * @return $this
     */
    private function addSegment($primary, $secondary = null) {

        $addSegment = is_null($secondary) ? $primary : $primary . '-' . $secondary;

        if ($this->hasSegment($addSegment)) {
            $this->statusAppend .= 'The segment [' . $addSegment . '] already exists. <br>';
        } else {
            $this->segments[] = $addSegment;
        }

        return $this;
    }

    public function status() {

        echo '<pre>';
        print_r($this->segments);
        echo '</pre>' . $this->statusAppend;

        return $this;
    }

}

class vertex {

    private $vertices = [];
    private $answer = [];

    /**
     * 
     * @param type $vertices
     */
    public function setVertices($vertices) {
        $this->vertices = $vertices;
    }

    /**
     * 
     * @param type $answer
     */
    public function setAnswer($answer) {
        $this->answer = $answer;
    }

    /**
     * 
     * @return type
     */
    public function getVertices() {
        return $this->vertices;
    }

    /**
     * 
     * @return type
     */
    public function getAnswer() {
        return $this->answer;
    }

    /**
     * 
     */
    public function status() {
        echo '<pre>';
        print_r($this->answer);
        print_r($this->vertices);
        echo '</pre>';
    }

}

class engine {

    public static $solutionSet;
    public $clones = [];
    public $seg;
    public $ver;
    public $map;
    public $startingVertex;
    public $vertices;

    /**
     * 
     */
    public function mergeReflections() {
        
    }

    /**
     * 
     * @param segment $seg
     * @param vertex $ver
     * @param type $map
     * @return $this
     */
    public function setStage(segment $seg, vertex $ver, $map) {
        $this->seg = $seg;
        $this->ver = $ver;
        $this->map = $map;
        return $this;
    }

    /**
     * 
     * @return $this
     */
    public function stagePlayers() {
        $this->seg->generate($this->map);
        $this->generate($this->map);
        return $this;
    }

    /**
     * 
     * @param type $vertex
     * @return $this
     */
    public function setStartingVertex($vertex) {
        $this->startingVertex = $vertex;

        $this->clones[] = clone $this->ver;
        //set init values

        $this->clones[0]->setVertices($this->vertices);
        $this->clones[0]->setAnswer([$vertex]);

        return $this;
    }

    /**
     * 
     * @param type $d
     * @return $this
     */
    public function findSolutions($d = 0) {

        $clones = $this->clones;
        unset($this->clones);
        //get the point
        foreach ($clones as $value) {
            $answerArr = $value->getAnswer(); //get the last value of array
            $vertexArr = $value->getVertices();

            foreach ($vertexArr[end($answerArr)] as $val) {

                $this->clones[] = clone $value;
                $ref = count($this->clones) - 1;

                $ansTemp = $this->clones[$ref]->getAnswer();

                $ansTemp[] = $val;

                $this->clones[$ref]->setAnswer($ansTemp);

                $verTemp = $this->clones[$ref]->getVertices();

                // remove from current $this->vertices
                unset($verTemp[end($answerArr)][$val]);

                // remove from current $this->vertices
                unset($verTemp[$val][end($answerArr)]);

                $this->clones[$ref]->setVertices($verTemp);
            }
        }

        if (empty($this->clones)) {
            $this->clones = $clones;
        }

        if ($d < count($this->seg->getSegments()) - 1) {
            $d++;
            $this->findSolutions($d);
        }

        return $this;
    }

    /**
     * 
     * @param type $vertexMapArr
     * @return $this
     */
    public function generate($vertexMapArr) {

        foreach ($vertexMapArr as $value) {
            $this->addVertexMap($value);
        }

        return $this;
    }

    /**
     * 
     * @param type $vertexMap
     * @return boolean
     */
    private function addVertexMap($vertexMap) {

        if (empty($vertexMap)) {
            echo "The array for {$vertexMap} is empty";
            return FALSE;
        }

        if (1 == count($vertexMap)) {
            echo "There are no ajdacent points set for {$vertexMap[0]}";
            return FALSE;
        }

        $sCentralPoint = $vertexMap[0];
        array_shift($vertexMap);


        $this->vertices[$sCentralPoint] = array_combine($vertexMap, $vertexMap);
    }

    /**
     * 
     * @return $this
     */
    public function status() {

        $output = '';


        foreach ($this->clones as $clone) {
            if (count($clone->getAnswer()) != count($this->seg->getSegments())) {
                $output .= implode("->", $clone->getAnswer()) . '<br>';
            }
        }

        if ('' == $output) {
            $output .= 'There are no valid solutions when starting at vertex \'' . $this->startingVertex . '\'<br><br>';
        } else {
            $output = 'Here are the valid solutions when starting at vertex \'' . $this->startingVertex . '\'<br><br>' . $output;
        }

        echo $output . '<br><br>';

        //bonus add
        engine::$solutionSet[] = $this->clones;

        return $this;
    }

}

$s = new segment();
$v = new vertex();

$puzzleA = [['e', 'c', 'd'],
    ['c', 'e', 'd', 'a', 'b'],
    ['d', 'e', 'c', 'a', 'b'],
    ['a', 'c', 'd', 'b'],
    ['b', 'c', 'd', 'a']];

$e = new engine();
$e->setStage($s, $v, $puzzleA)
        ->stagePlayers()
        ->setStartingVertex('a')
        ->findSolutions()
        ->status();


$puzzleB = [['f', 'd', 'e'],
    ['c', 'd', 'e', 'a', 'b'],
    ['d', 'a', 'c', 'e'],
    ['a', 'd', 'c', 'b'],
    ['b', 'a', 'c', 'e'],
    ['e', 'f', 'd', 'c', 'b']];

$ee = new engine();
$ee->setStage($s, $v, $puzzleB)
        ->stagePlayers()
        ->setStartingVertex('e')
        ->findSolutions()
        ->status();


