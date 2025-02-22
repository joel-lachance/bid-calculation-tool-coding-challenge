<template>
    <div>
      <form>

        <fieldset>
            <label for="phone">Vehicle base price:</label>
            <input type="text" id="base_price" name="base_price" v-model="formData.base_price" required> $
        </fieldset>

        <fieldset>
            <label for="phone">Vehicle type:</label>
            <select id="type" name="type" v-model="formData.type" required>
                <option value="car">Type of vehicule</option>
                <option value="common">Common</option>
                <option value="luxury">Luxury</option>
            </select>
        </fieldset>

      </form>

        <div v-if="response">
            <h3>Fees:</h3>
            <ul>
                <li>Basic Buyer's Fee: <span>{{ formatCurrency(response.fees.basic) }}</span></li>
                <li>seller's special fee: <span>{{ formatCurrency(response.fees.special) }}</span></li>
                <li>Association fee: <span>{{ formatCurrency(response.fees.association) }}</span></li>
                <li>Storage fee: <span>{{ formatCurrency(response.fees.fixed) }}</span></li>
                <li><strong>Total: <span>{{ formatCurrency(response.total) }}</span></strong></li>
            </ul>
        </div>

        <div v-if="error">
            <h3 style="color: red;">Error:</h3>
            <p>{{ error }}</p>
        </div>


    </div>
  </template>

<script>
import { throttle } from "lodash";

export default {

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
        onInputChange() {
            if (this.formData.base_price && this.formData.type) {
                this.throttledSubmitForm();
            } else {
                this.response = null;
                this.error = "Please fill in both fields.";
            }
        },

        async submitForm() {

            this.response = null;
            this.error = null;

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