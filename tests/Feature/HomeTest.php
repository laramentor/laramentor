<?php

test('test homepage is displayed', function () {

    $response = $this->get('/');

    $response->assertStatus(200);
});
