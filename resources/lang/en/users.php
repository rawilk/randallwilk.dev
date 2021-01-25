<?php
return [
    "actions" => [
        "confirm_password_button" => "Confirm",
        "confirm_password_text" => "For your security, please confirm your password to continue.",
        "confirm_password_title" => "Confirm Password",
        "delete_desc" => "Permanently delete :name account and any data associated with it.",
        "delete_title" => "Delete User",
        "impersonate_button" => "Impersonate",
        "impersonate_desc" => "See what :name sees by impersonating their user account.",
        "impersonate_title" => "Impersonate User"
    ],
    "alerts" => [
        "bulk_deleted" => "You've deleted :count user.|You've deleted :count users.",
        "created" => "User account created successfully.",
        "deleted" => ":name was deleted!",
        "no_results" => "No users found..."
    ],
    "form" => [
        "create_title" => "Create User",
        "labels" => [
            "avatar" => "Avatar",
            "cancel_avatar_upload" => "Cancel Upload",
            "current_password" => "Current Password",
            "email" => "Email",
            "email_placeholder" => "tom.cooke@:domain",
            "is_admin" => "Is Admin",
            "is_admin_help" => "Giving a user administrator rights will give them access to the admin panel.",
            "name" => "Name",
            "name_placeholder" => "Tom Cooke",
            "new_password" => "New Password",
            "new_user_password_help" => "Leave blank to generate a random password for the user. The new user will be emailed their password when created.",
            "password" => "Password",
            "password_confirmation" => "Confirm Password",
            "remove_avatar" => "Remove Photo",
            "select_new_avatar" => "Select A New Photo",
            "timezone" => "Timezone",
            "your_password" => "Your Password",
            "your_password_help" => "We require your password for an added layer of security."
        ]
    ],
    "impersonate" => ["leave" => "Leave impersonation", "notice" => "You are impersonating :name."],
    "labels" => [
        "account_info_sub_title" => "Configure the user's account.",
        "account_info_title" => "Account Information",
        "delete_modal_button" => "Delete User",
        "delete_modal_text" => "This will permanently delete <strong>:name</strong>! You will not be able to recover any data associated with this user.",
        "delete_modal_title" => "Delete User",
        "email" => "Email",
        "filter_created_at_max" => "Created Max",
        "filter_created_at_min" => "Created Min",
        "filter_id_max" => "Max ID",
        "filter_id_min" => "Min ID",
        "filter_updated_at_max" => "Last Updated Max",
        "filter_updated_at_min" => "Last Updated Min",
        "is_admin" => "Is Admin",
        "name" => "Name",
        "profile_info_sub_title" => "Specify the user's profile information and email address.",
        "profile_info_title" => "Profile Information",
        "profile_info_update_sub_title" => "Update the user account's profile information and email address.",
        "timezone" => "Timezone",
        "update_password_sub_title" => "Ensure the account is using a long, random password to stay secure.",
        "update_password_title" => "Update Password"
    ],
    "page_title" => "Users",
    "profile" => [
        "account_info" => [
            "confirm_delete_button" => "Delete Account",
            "confirm_delete_text" => "Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.",
            "confirm_delete_title" => "Delete Account",
            "delete_account_trigger_button" => "Delete account",
            "delete_user_title" => "Delete your account",
            "delete_user_warning" => "Once your account is deleted, all of its resources and data will be permanently deleted. Please be sure this is what you want to do.",
            "page_title" => "My Account",
            "profile_info_sub_title" => "Update your account's profile information and email address.",
            "profile_info_title" => "Profile Information",
            "update_password_sub_title" => "Ensure your account is using a long, random password to stay secure.",
            "update_password_title" => "Update Password"
        ],
        "authentication" => [
            "account_was_disconnected" => ":provider account disconnected.",
            "connect_to_provider_button" => "Connect to :provider account",
            "connected_account" => "Connected account: :username",
            "disconnect_from_provider_button" => "Disconnect from :provider",
            "page_title" => "Authentication Settings",
            "social_auth_sub_title" => "Connect to a social account to sign in with it.",
            "social_auth_title" => "Social Authentication",
            "social_github_connect_desc" => "Sign in without a password using your GitHub account.",
            "social_github_title" => "GitHub"
        ],
        "tabs" => ["account_info" => "Account Information", "authentication" => "Authentication"]
    ],
    "singular" => "User",
    "tabs" => ["account_info" => "Account Information"]
];
