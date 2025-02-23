<template>
    <v-app>
        <v-container>
            
            <v-alert
                text="Please enter the vehicle's base price and type to get the list of related fees."
                title="Bid Calculation Tool"
                type="info"
                class="mb-4"></v-alert>

            <v-alert v-if="error"
                title="An error occurred"
                type="error"
                class="mb-4">{{ error }}</v-alert>

            <v-row>

                <v-col cols="12" md="4">
                    <v-text-field 
                        v-model="formData.base_price" 
                        type="number"
                        label="Vehicule base price"
                        append-inner-icon="mdi-currency-usd"
                        required></v-text-field>
                </v-col>

                <v-col cols="12" md="4">
                    <v-select
                        v-model="formData.type"
                        label="Type of vehicule"
                        :items="[{ title: 'Common', value: 'common' }, { title: 'Luxury', value: 'luxury' }]"
                        append-inner-icon="mdi-car-select"
                        ></v-select>
                </v-col>

            </v-row>

            <v-list v-if="response">
                <v-list-item-group>
                    <!-- Basic Buyer's Fee -->
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Basic Buyer's Fee:</v-list-item-title>
                            <v-list-item-subtitle>{{ formatCurrency(response.fees.basic) }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <!-- Seller's Special Fee -->
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Seller's Special Fee:</v-list-item-title>
                            <v-list-item-subtitle>{{ formatCurrency(response.fees.special) }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <!-- Association Fee -->
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Association Fee:</v-list-item-title>
                            <v-list-item-subtitle>{{ formatCurrency(response.fees.association) }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <!-- Storage Fee -->
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title>Storage Fee:</v-list-item-title>
                            <v-list-item-subtitle>{{ formatCurrency(response.fees.fixed) }}</v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>

                    <!-- Total -->
                    <v-list-item>
                        <v-list-item-content>
                            <v-list-item-title><strong>Total:</strong></v-list-item-title>
                            <v-list-item-subtitle><strong>{{ formatCurrency(response.total) }}</strong></v-list-item-subtitle>
                        </v-list-item-content>
                    </v-list-item>
                </v-list-item-group>
            </v-list>
                   
        </v-container>
    </v-app>
  </template>

<script>
import { throttle } from "lodash";

export default {


    name: "App",

    data() {
        return {
            formData: {
                base_price: "",
                type: "",
            },
            response: null,
            error: null,
        };
    },

    watch: {
        formData: {
            handler: "onInputChange",
            deep: true,
        },
    },

    created() {
        // Debounced submitForm method to prevent multiple stack calls
        this.throttledSubmitForm = throttle(this.submitForm, 500, { leading: true, trailing: true });
    },

    methods: {

        // Format currency
        formatCurrency(value) {
            return value ? `${parseFloat(value).toFixed(2)}$` : "0.00$";
        },

        // On form input change, validate form data is empty before calling API
        // Reset error alert if any
        onInputChange() {

            this.error = null;

            if (this.formData.base_price && this.formData.type) {
                this.throttledSubmitForm();
            }
        },

        async submitForm() {

            this.response = null;
            this.error = null;

            if (!this.formData.base_price || !this.formData.type) {
                return;
            }

            const formData = new FormData();
            formData.append("base_price", this.formData.base_price);
            formData.append("type", this.formData.type);

            try {
                const res = await fetch("https://bid-calculation-tool.ddev.site/api/bid-calculator", {
                    method: "POST",
                    body: formData,
                });

                if (!res.ok) {
                    throw new Error("Failed to fetch data.");
                }

                this.response = await res.json();
            } catch (err) {
                this.error = err.message;
            }
        },
    },
};
</script>