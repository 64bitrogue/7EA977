<?php

function sanitize_input($input) {
    return trim(htmlspecialchars(stripslashes($input)));
}