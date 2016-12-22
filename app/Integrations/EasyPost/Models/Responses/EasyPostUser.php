<?php

namespace App\Integrations\EasyPost\Models\Responses;


class EasyPostUser
{

    /**
     * Unique, begins with "user_"
     * @var	string
     */
    protected $id;

    /**
     * "User"
     * @var	string
     */
    protected $object;

    /**
     * The ID of the parent user object. Top-level users are defined as users with no parent
     * @var	string
     */
    protected $parent_id;

    /**
     * First and last name required
     * @var	string
     */
    protected $name;

    /**
     * Required
     * @var	string
     */
    protected $email;

    /**
     * Optional
     * @var	string
     */
    protected $phone_number;

    /**
     * Formatted as string "XX.XXXXX"
     * @var	string
     */
    protected $balance;

    /**
     * USD formatted dollars and cents, delimited by a decimal point
     * @var	string
     */
    protected $recharge_amount;

    /**
     * USD formatted dollars and cents, delimited by a decimal point
     * @var	string
     */
    protected $secondary_recharge_amount;

    /**
     * Number of cents USD that when your balance drops below, we automatically recharge your account with your primary payment method.
     * @var	string
     */
    protected $recharge_threshold;

    /**
     * All associated children
     * @var	EasyPostUser[]
     */
    protected $children;


}