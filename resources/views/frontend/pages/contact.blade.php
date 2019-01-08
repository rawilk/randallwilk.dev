@extends('layouts.frontend-app')
@section('title', 'Contact Me')

@push('meta')
    @php
        $pageDescription = 'Send a friendly hello to me or start a project with me.';
    @endphp

    <meta name="description" content="{{ $pageDescription }}">
    <meta property="og:type" content="article">
    <meta property="og:description" content="{{ $pageDescription }}">
@endpush

@section('content')
    @php
        $breadcrumbs = [
            ['url' => '#', 'display' => 'Contact']
        ];
    @endphp

    @component('frontend.components.page-header', ['breadcrumbs' => $breadcrumbs])
        @slot('title')
            Contact
        @endslot

        @slot('subtitle')
            Get in touch with me!
        @endslot
    @endcomponent

    <div class="container">
        <div class="row py-4">
            <div class="col-lg-6">
                <div class="overflow-hidden mb-1">
                    <h2 class="font-weight-normal text-7 mt-2 mb-0">
                        <strong class="font-weight-extra-bold">Contact</strong>
                        Me
                    </h2>
                </div>
                <div class="overflow-hidden mb-4 pb-3">
                    <p class="mb-0">Feel free to send me a message or ask me any questions!</p>
                </div>
                <contact-form inline-template>
                    <b-form @submit.prevent="submit" v-cloak>
                        <b-row>
                            <b-col lg="6">
                                <b-form-group label="Full Name *" field="name">
                                    <b-input v-model="contact.name"
                                             required
                                             maxlength="100"
                                             v-validate="'required|max:100'"
                                             data-vv-name="name"
                                    >
                                    </b-input>
                                </b-form-group>
                            </b-col>
                            <b-col lg="6">
                                <b-form-group label="Email Address *" field="email">
                                    <b-input v-model="contact.email"
                                             type="email"
                                             required
                                             v-validate="'required|email'"
                                             data-vv-name="email"
                                    >
                                    </b-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <b-form-group label="Subject" field="subject">
                                    <b-input v-model="contact.subject"
                                             maxlength="100"
                                             v-validate="'max:100'"
                                             data-vv-name="subject"
                                             placeholder="Optional"
                                    >
                                    </b-input>
                                </b-form-group>
                            </b-col>
                        </b-row>
                        <b-row>
                            <b-col>
                                <b-form-group label="Message *" field="message">
                                    <b-textarea
                                        v-model="contact.message"
                                        required
                                        maxlength="5000"
                                        rows="8"
                                        v-validate="'required|max:5000'"
                                        data-vv-name="message"
                                        placeholder="Be as detailed as possible"
                                    >
                                    </b-textarea>
                                </b-form-group>
                            </b-col>
                        </b-row>

                        <b-row class="mb-lg-0 mb-4">
                            <b-col>
                                <b-btn type="submit" variant="primary" :busy="form.busy">
                                    Send Message
                                </b-btn>
                            </b-col>
                        </b-row>
                    </b-form>
                </contact-form>
            </div>
            <div class="col-lg-6">
                <div>
                    <h4 class="font-weight-normal mt-2 mb-1">
                        My <strong class="font-weight-extra-bold">Information</strong>
                    </h4>
                    <ul class="list list-icons list-icons-style-2 mt-3">
                        <li>
                            <i class="mdi mdi-cellphone"></i>
                            <strong class="text-dark">Phone:</strong>
                            {{ config('randallwilk.contact_phone') }}
                        </li>
                        <li>
                            <i class="mdi mdi-email"></i>
                            <strong class="text-dark">Email:</strong>
                            {{ config('randallwilk.contact_email') }}
                        </li>
                    </ul>
                </div>
                <h4 class="font-weight-normal pt-5b">
                    Follow <strong class="font-weight-bold">Me</strong>
                </h4>

                @component('frontend.components.social-icons')
                    @slot('classes')
                        social-icons-big social-icons-spaced social-icons-clean social-icons-bordered
                    @endslot
                @endcomponent

                <h4 class="font-weight-normal pt-5b">
                    Get in <strong class="font-weight-extra-bold">Touch</strong>
                </h4>
                <p class="lead mb-0 text-4">
                    I will be more than happy to answer any of your questions or talk to you.
                    You can use this form to contact me or to start a project with me.
                </p>
            </div>
        </div>
    </div>
@endsection

@push('js')
    <script src="{!! mix('js/frontend/pages/contact/index.js') !!}"></script>
@endpush
