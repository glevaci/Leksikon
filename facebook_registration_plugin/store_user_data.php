            <?php
            define('FACEBOOK_APP_ID', 'YOUR_APP_ID_HERE');
            define('FACEBOOK_SECRET', 'YOUR_APP_SECRET_HERE');

			// No need to change function body
            function parse_signed_request($signed_request, $secret) {
                list($encoded_sig, $payload) = explode('.', $signed_request, 2);

                // decode the data
                $sig = base64_url_decode($encoded_sig);
                $data = json_decode(base64_url_decode($payload), true);

                if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
                    error_log('Unknown algorithm. Expected HMAC-SHA256');
                    return null;
                }

                // check sig
                $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
                if ($sig !== $expected_sig) {
                    error_log('Bad Signed JSON signature!');
                    return null;
                }

                return $data;
            }

            function base64_url_decode($input) {
                return base64_decode(strtr($input, '-_', '+/'));
            }

            if ($_REQUEST) {
                $response = parse_signed_request($_REQUEST['signed_request'],
                                FACEBOOK_SECRET);
				/*
				echo "<pre>";
				print_r($response);
				echo "</pre>"; // Uncomment this for printing the response Array
				*/
                $name = $response["registration"]["name"];
                $email = $response["registration"]["email"];
                $password = $response["registration"]["password"];
                $gender = $response["registration"]["gender"];
                $dob = $response["registration"]["birthday"];
                $phone = $response["registration"]["phone"];

                // Connecting to Database
                mysql_connect('DATABASE_HOST', 'YOUR_USERNAME', 'YOUR_PASSWORD');
                mysql_select_db('YOUR_DATABASE');

                $result = mysql_query("INSERT INTO users (name, email, password, gender, dob, phone) VALUES ('$name', '$email', '$password', '$gender', '$dob', $phone)");
                if ($result) {
                    // User successfully stored
                    // Redirect to some page
                } else {
                    // Error
                    // Redirect to error page
                }
            } else {
                echo '$_REQUEST is empty';
            }
            ?>
      