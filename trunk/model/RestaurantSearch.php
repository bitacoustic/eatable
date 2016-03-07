<?php

include_once 'model/Model.php';
include_once 'model/Restaurant.php';

/*
 * The RestaurantSearch class handles all restaurant queries to the db.
 */

class RestaurantSearch extends Model {

    private $searchTerm;  // The term entered in the search field.
    private $searchLocation; // The location to perform the search.
    private $numOfResults;  // The number of search results to return.

    /*
     * The constructor initialize the members.
     */

    public function __construct($searchType = NIL, $searchTerm, $searchLocation, $numOfResults = 10) {
        $this->searchType = $searchType;
        $this->searchTerm = $searchTerm;
        $this->searchLocation = $searchLocation;
        $this->numOfResults = $numOfResults;
    }

    /**
     * Search multiple keywords by multiple columns in the Restaurants table.
     */
    public function searchByName() {
        $conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
        $searchTerms = $conn->escape_string($this->searchTerm);
        $conn->close();
        
        // Turn the search terms string into an array of words tokenized by
        // ',' or whitespace
        $searchTerms = str_replace(",", " ", $searchTerms);
        $searchTerms = preg_replace("/[\s]+/", " ", $searchTerms);
        $searchTerms = explode(" ", $searchTerms);
        
        $sql = "SELECT * FROM Restaurants R, Images I WHERE \n";
        if (count($searchTerms) > 0) {
            foreach($searchTerms as $term) {
                $sql .= "(
                    R.name LIKE '%$term%' OR
                    R.keywords LIKE '%$term%' OR
                    R.cuisine_type LIKE '%$term%' OR
                    R.address_street LIKE '%$term%' OR
                    R.address_city LIKE '%$term%' OR
                    R.address_state LIKE '%$term%' OR
                    R.address_postal_code LIKE '%$term%'
)
AND\n";
            }
        }
        $sql .= "R.id = I.restaurant_id;";
//        echo "<p><pre>$sql</pre></p>"; //TEST
        
        // Query the database.
        $results = parent::queryDatabase($sql);
        
        // Create an array of restaurant objects from the query results.
        if (0 < $results->num_rows) {
            $restaurantArray = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new Restaurant();
                $instance->id = $row['restaurant_id'];
                $instance->name = $row['name'];
                $instance->addressStreet = $row ['address_street'];
                $instance->addressCity = $row ['address_city'];
                $instance->addressState = $row ['address_state'];
                $instance->addressPostalCode = $row ['address_postal_code'];
                $instance->phoneNumber = $row ['phone_number'];
                $instance->keywords = $row['keywords'];
                $instance->image = $row ['image'];
                $instance->approved = $row ['approved'];
                $instance->cuisineType = $row ['cuisine_type'];
                array_push($restaurantArray, $instance);
            }

            // Return the array of restaurant objects.
            return $restaurantArray;
        } else {
            // If there are no results, return NIL.
            return NIL;
        }
    }
    
    /**
     * DEPRECATED.
     * Search the Restaurants table's name field for the term.
     * Old version which did not match multiple keywords against multiple
     * columns.
     */
    public function searchByName1() {
        $table = 'Restaurants R, Images I';
//		$table = 'Restaurants';
        // Build the query.
        /*
         * QUERY DOES A LIKE AGAINST THE NAME AND KEYWORDS FIELDS IN THE DATABASE.
         * EX. IF THERE IS A RESTAURANT NAMED BOB'S BURGER'S IT WILL BE RETURNED
         * 	   IN A SEARCH FOR JUST BURGERS EVEN IF BURGERS IS NOT IN ITS KEYWORDS.
         */
        $sql = "SELECT * FROM " . $table . " WHERE (R.name LIKE '%" . $this->searchTerm
                . "%' OR R.keywords LIKE '%" . $this->searchTerm
                . "%' OR R.cuisine_type LIKE '%" . $this->searchTerm
                . "%' OR R.address_street LIKE '%" . $this->searchTerm
                . "%' OR R.address_city LIKE '%" . $this->searchTerm
                . "%' OR R.address_state LIKE '%" . $this->searchTerm
                . "%' OR R.address_postal_code LIKE '%" . $this->searchTerm
                . "%') AND R.id = I.restaurant_id;";
        // Query the database.
        $results = parent::queryDatabase($sql);
        // Create an array of restaurant objects from the query results.

        if (0 < $results->num_rows) {
            $restaurantArray = array();
            while ($row = $results->fetch_assoc()) {
                $instance = new Restaurant();
                $instance->id = $row['restaurant_id'];
                $instance->name = $row['name'];
                $instance->addressStreet = $row ['address_street'];
                $instance->addressCity = $row ['address_city'];
                $instance->addressState = $row ['address_state'];
                $instance->addressPostalCode = $row ['address_postal_code'];
                $instance->phoneNumber = $row ['phone_number'];
                $instance->keywords = $row['keywords'];
                $instance->image = $row ['image'];
                $instance->approved = $row ['approved'];
                $instance->cuisineType = $row ['cuisine_type'];
                array_push($restaurantArray, $instance);
            }

            // Return the array of restaurant objects.
            return $restaurantArray;
        } else {
            // If there are no results, return NIL.
            return NIL;
        }
    }

    /*
     * Search the Restaurants table's name field for the term.
     */

    public function searchByCity() {
        $table = 'Restaurants R, Images I';
//		$table = 'Restaurants';
        // Build the query.
        /*
         * QUERY DOES A LIKE AGAINST THE NAME AND KEYWORDS FIELDS IN THE DATABASE.
         * EX. IF THERE IS A RESTAURANT NAMED BOB'S BURGER'S IT WILL BE RETURNED
         * 	   IN A SEARCH FOR JUST BURGERS EVEN IF BURGERS IS NOT IN ITS KEYWORDS.
         */
        $sql = "SELECT * FROM " . $table . " WHERE R.address_city LIKE '%" . $this->searchLocation . "%' AND R.id = I.restaurant_id;";
        // Query the database.
        $results = parent::queryDatabase($sql);
        // Create an array of restaurant objects from the query results.
        if (0 < $results->num_rows) {
            while ($row = $results->fetch_assoc()) {
                $restaurantArray[] = new Restaurant($row["restaurant_id"], $row["name"], $row["address_street"], $row["address_city"], $row["address_state"], $row["address_postal_code"], $row["phone_number"], $row["keywords"], $row["image"]);
            }

            // Return the array of restaurant objects.
            return $restaurantArray;
        } else {
            // If there are no results, return NIL.
            return NIL;
        }
    }

    /*
     * GETTER AND SETTERS.
     */

    public function getSearchTerm() {
        return $this->searchTerm;
    }

}
