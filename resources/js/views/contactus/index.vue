<template>
  <div>
    <page-title-bar></page-title-bar>
    <v-container fluid class="grid-list-xl pt-0 mt-n3">
      <v-row class="contactus">
        <v-col class="back-overlay"></v-col>
        <v-col cols="12" md="6" lg="6" sm="6" class="col-height-auto">
          <v-card class="pa-6" style="background-color: #2e3344b3">
            <v-row>
              <v-col cols="10" class="pb-0">
                <h3>How to Find Us</h3>
              </v-col>
              <v-col cols="10" class="pb-0">
                <p>
                  If you have any questions, just fill in the contact form, and
                  we will answer you shortly. If you are living nearby, come
                  visit at one of our comfortable offices.
                </p>
              </v-col>
              <v-col cols="10" class="pb-0">
                <h4>Headquarters</h4>
                <p>
                  <!-- 9863 - 9867 MILL ROAD, CAMBRIDGE, MG09 99HT.<br /> -->
                  Telephone : TBA <br />
                  E-mail : admin@eaglefx.co.uk
                </p>
              </v-col>
              <v-col cols="10" class="pb-0">
                <h4>Support Centre</h4>
                <p>
                  <!-- 9870 ST VINCENT PLACE, GLASGOW, DC 45 FR 45 <br /> -->
                  Telephone : TBA <br />
                  E-mail : admin@eaglefx.co.uk
                  <br />
                  <br />
                  <br />
                  <br />
                  <br />
                  <br />
                  <br />
                  <br />
                  <br />
                </p>
              </v-col>
            </v-row>
          </v-card>
        </v-col>
        <v-col cols="12" md="6" lg="6" sm="6" class="col-height-auto">
          <v-card class="pa-6" style="background-color: #2e3344b3">
            <v-form v-model="form.valid" ref="form" lazy-validation>
              <v-row>
                <v-col cols="12" class="pb-0">
                  <h3>Get In Touch</h3>
                </v-col>
                <v-col cols="12" class="pb-0">
                  <v-text-field
                    label="Name"
                    v-model="form.name"
                    :rules="form.nameRules"
                    required
                    class="py-0"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" class="pb-0">
                  <v-text-field
                    label="E-mail"
                    v-model="form.email"
                    :rules="form.emailRules"
                    required
                    class="py-0"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" class="pb-0">
                  <v-text-field
                    label="Phone"
                    v-model="form.phone"
                    :rules="form.phoneRules"
                    required
                    class="py-0"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" class="pb-0">
                  <v-text-field
                    label="Subject"
                    v-model="form.subject"
                    :rules="form.subjectRules"
                    required
                    class="py-0"
                  ></v-text-field>
                </v-col>
                <v-col cols="12" class="pb-0">
                  <v-textarea
                    label="Message"
                    v-model="form.message"
                    :rules="form.messageRules"
                    required
                    class="py-0"
                  ></v-textarea>
                </v-col>
                <v-col cols="12 text-right">
                  <v-btn
                    :disabled="!form.valid"
                    color="success"
                    class="mr-3"
                    @click="onSubmit"
                  >
                    Submit
                  </v-btn>
                </v-col>
              </v-row>
            </v-form>
          </v-card>
        </v-col>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import webServices from "WebServices";
import dateformat from "dateformat";
import axios from "axios";
import Vue from "vue";
import Nprogress from "nprogress";
import { mapActions } from "vuex";

export default {
  data() {
    return {
      form: {
        valid: true,
        name: "",
        nameRules: [(v) => !!v || "Name is required"],
        email: "",
        emailRules: [
          (v) => !!v || "E-mail is required",
          (v) =>
            /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
            "E-mail must be valid",
        ],
        phone: null,
        phoneRules: [(v) => !!v || "Phone number is required"],
        subject: "",
        subjectRules: [(v) => !!v || "Subject is required"],
        message: "",
        messageRules: [(v) => !!v || "Message is required"],
      },
    };
  },
  mounted() {},
  methods: {
    onSubmit() {
      if (this.$refs.form.validate()) {
        let data = {
          name: this.form.name,
          email: this.form.email,
          phone: this.form.phone,
          subject: this.form.subject,
          message: this.form.message,
        };
        axios
          .post(`${webServices.baseURL}/contact-us/send`, data, {
            headers: {
              "Content-Type": "application/json",
            },
          })
          .then(() => {
            Vue.notify({
              group: "signals",
              type: "success",
              text: "Your message has been sent successfully!",
            });
          })
          .catch(() => {
            Vue.notify({
              group: "signals",
              type: "error",
              text: "Error occurred",
            });
          })
          .finally(() => {});
      }
    },
  },
};
</script>