<template>
  <div>
    <page-title-bar></page-title-bar>
    <v-container fluid class="grid-list-xl pt-0 mt-n3">
      <v-row>
        <v-col cols="12" md="12" lg="12" sm="12" class="col-height-auto">
          <div v-if="loading">
            <div class="profile-head app-card mb-30 mt-30">
              <div class="user-list-content">
                <div class="text-center">
                  <h3 class="fw-bold">Loading ...</h3>
                </div>
              </div>
            </div>
          </div>
          <div v-else>
            <div v-if="user" class="profile-head app-card mb-30">
              <div class="user-profile-widget top-author-wrap">
                <div class="avatar-wrap mb-50 pos-relative">
                  <span class="overlay-content"></span>
                  <div class="user-info">
                    <input
                      type="file"
                      accept="image/*"
                      ref="profileimage"
                      style="display: none"
                      @change="changeAvatar"
                    />
                    <v-tooltip right>
                      <template v-slot:activator="{ on }">
                        <img
                          v-on="on"
                          @click="$refs.profileimage.click()"
                          :src="
                            user.avatar
                              ? user.avatar
                              : '/static/avatars/default.png'
                          "
                          alt="reviwers"
                          width="100"
                          height="100"
                          class="img-responsive rounded-circle mr-3"
                          style="cursor: pointer"
                        />
                      </template>
                      <span>Click Here to change avatar</span>
                    </v-tooltip>
                    <div class="white--text pt-7">
                      <h1 class="mb-0" style="text-transform: uppercase">
                        {{ user.name }}
                      </h1>
                    </div>
                  </div>
                </div>
                <div class="author-detail-wrap">
                  <div class="pa-3 authors-info">
                    <ul class="list-unstyled author-contact-info mb-2">
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-email"></i> Email</span>
                        </span>
                        <span
                          class="fs-20 grey--text fw-normal d-custom-flex align-items-left w-70"
                          >{{ user.email }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-accounts-alt"></i> Group</span
                          >
                        </span>
                        <span
                          class="fs-20 grey--text fw-normal d-custom-flex align-items-left w-70"
                          >{{ groupLabel }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-calendar-check"></i> Experience
                            Year</span
                          >
                        </span>
                        <span
                          class="fs-20 grey--text fw-normal d-custom-flex align-items-left w-70"
                          >{{ user.experience_year }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-format-list-bulleted"></i>
                            Experience History</span
                          >
                        </span>
                        <span
                          class="fs-20 grey--text fw-normal d-custom-flex align-items-left w-70"
                          >{{ user.experience_history }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-phone-msg"></i> Phone</span>
                        </span>
                        <span
                          class="fs-20 grey--text fw-normal d-custom-flex align-items-left w-70"
                          >{{ user.phone }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-calendar-alt"></i>
                            Birthday</span
                          >
                        </span>
                        <span
                          class="fs-20 grey--text fw-normal d-custom-flex align-items-left w-70"
                          >{{ getDateFormatBirth(user.date_of_birth) }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-map"></i> Country</span>
                        </span>
                        <span
                          class="fs-20 grey--text fw-normal d-custom-flex align-items-left w-70"
                          ><v-img :src="user.flag" max-width="30"></v-img>
                          &nbsp; {{ user.country }}
                        </span>
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-pin"></i> Address</span>
                        </span>
                        <span
                          class="fs-20 grey--text fw-normal d-custom-flex align-items-left w-70"
                          >{{ user.address }}</span
                        >
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span><i class="zmdi zmdi-code"></i> Zip Code</span>
                        </span>
                        <span
                          class="fs-20 grey--text fw-normal d-custom-flex align-items-left w-70"
                        >
                          {{ user.zipcode }}
                        </span>
                      </li>
                      <li class="d-flex px-4 align-center">
                        <span class="mr-3 d-custom-flex align-items-left w-30">
                          <span
                            ><i class="zmdi zmdi-calendar-check"></i> Member
                            Since</span
                          >
                        </span>
                        <span
                          class="fs-20 grey--text fw-normal d-custom-flex align-items-left w-70"
                          >{{ getDateFormat(user.created_at) }}</span
                        >
                      </li>
                    </ul>
                    <ul class="d-custom-flex social-info list-unstyled">
                      <li>
                        <a class="facebook" href="https://www.facebook.com"
                          ><i class="zmdi zmdi-facebook-box"></i
                        ></a>
                      </li>
                      <li>
                        <a class="twitter" href="https://www.twitter.com"
                          ><i class="zmdi zmdi-twitter-box"></i
                        ></a>
                      </li>
                      <li>
                        <a class="linkedin" href="https://www.linkedin.com"
                          ><i class="zmdi zmdi-linkedin-box"></i
                        ></a>
                      </li>
                      <li>
                        <a class="instagram" href="https://www.instagram.com"
                          ><i class="zmdi zmdi-instagram"></i
                        ></a>
                      </li>
                    </ul>
                  </div>
                  <div class="d-custom-flex align-center px-3 pb-3">
                    <v-btn
                      fab
                      class="mr-3"
                      dark
                      color="cyan"
                      @click="onEditUser()"
                    >
                      <v-icon dark>edit</v-icon>
                    </v-btn>
                    <v-btn fab @click="onDeleteUser()" dark color="red">
                      <v-icon class="grey--text font-lg">delete</v-icon>
                    </v-btn>
                  </div>
                  <ul
                    class="d-custom-flex list-unstyled footer-content text-center w-100 border-top-1 align-end"
                  >
                    <li>
                      <h5 class="mb-0">{{ trading_info.providers }}</h5>
                      <span class="fs-12 grey--text fw-normal">Providers</span>
                    </li>
                    <li>
                      <h5 class="mb-0">{{ trading_info.followers }}</h5>
                      <span class="fs-12 grey--text fw-normal">Followers</span>
                    </li>
                    <li>
                      <h5 class="mb-0">{{ trading_info.copiers }}</h5>
                      <span class="fs-12 grey--text fw-normal">Copiers</span>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div v-else class="profile-head app-card mb-30 mt-30">
              <div class="user-list-content">
                <div class="text-center">
                  <h3 class="fw-bold">User data is not available</h3>
                </div>
              </div>
            </div>
          </div>
        </v-col>
      </v-row>
      <delete-confirmation-dialog
        ref="deleteConfirmationDialog"
        heading="Are You Sure You Want To Delete?"
        message="Are you sure you want to delete this User permanently?"
        @onConfirm="deleteUser"
      >
      </delete-confirmation-dialog>
      <template>
        <v-dialog v-model="open" max-width="600">
          <v-card class="pa-6">
            <v-form v-model="form.valid" ref="form" lazy-validation>
              <img
                :src="
                  user && user.avatar
                    ? user.avatar
                    : '/static/avatars/default.png'
                "
                alt="reviwers"
                width="100"
                height="100"
                class="img-responsive rounded-circle mr-3"
              />
              <v-text-field
                label="Name"
                v-model="form.name"
                :rules="form.nameRules"
                :counter="30"
                required
              ></v-text-field>
              <v-text-field
                label="E-mail"
                v-model="form.email"
                :rules="form.emailRules"
                required
              ></v-text-field>
              <v-select
                :items="groupItems"
                label="Select Group"
                menu-props="auto"
                v-model="form.group"
                :rules="form.groupRules"
                required
                class="pb-2"
              ></v-select>
              <v-text-field label="Address" v-model="form.address">
              </v-text-field>
              <v-text-field label="ZipCode" v-model="form.zipcode">
              </v-text-field>
              <v-select
                :items="countryList"
                v-model="form.country"
                label="Country"
                item-text="name"
                item-value="index"
                menu-props="auto"
              >
                <template v-slot:selection="{ item }">
                  <v-img
                    max-width="30"
                    :src="item.flag"
                    class="flag-view"
                  ></v-img>
                  &nbsp;
                  {{ item.name }}
                </template>
                <template v-slot:item="{ item }">
                  <v-img
                    max-width="35"
                    :src="item.flag"
                    class="flag-view"
                  ></v-img>
                  &nbsp;
                  {{ item.name }}
                </template>
              </v-select>
              <v-text-field label="Phone" v-model="form.phone"> </v-text-field>
              <v-text-field
                label="Password"
                v-model="form.password"
                type="password"
                required
              >
              </v-text-field>
              <v-btn
                @click="editUser"
                :disabled="!form.valid"
                color="success"
                class="mr-3"
              >
                Update
              </v-btn>
              <v-btn color="primary" @click="open = false" class="px-4"
                >Cancel</v-btn
              >
            </v-form>
          </v-card>
        </v-dialog>
      </template>
    </v-container>
  </div>
</template>

<script>
import Vue from "vue";
import { mapGetters, mapActions } from "vuex";
import axios from "axios";
import dateformat from "dateformat";
import Nprogress from "nprogress";
import webServices from "WebServices";

export default {
  props: ["user_id"],
  data() {
    return {
      user: null,
      trading_info: null,
      loading: false,
      open: false,
      countryList: [],
      form: {
        valid: true,
        name: "",
        nameRules: [
          (v) => !!v || "Name is required",
          (v) =>
            (v && v.length <= 30) || "Name must be less than 30 characters",
        ],
        email: "",
        emailRules: [
          (v) => !!v || "E-mail is required",
          (v) =>
            /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
            "E-mail must be valid",
        ],
        country: "",
        address: "",
        zipcode: "",
        phone: null,
        password: false,
        group: "",
        groupRules: [(v) => !!v || "Please select Group Level"],
      },
    };
  },
  mounted() {
    this.loading = true;
    this.getGroups();
    Nprogress.start();
    axios
      .get(`${webServices.baseURL}/users/${this.user_id}`, {
        headers: { "Content-Type": "application/json" },
      })
      .then(({ data }) => {
        if (data.response.api_status) {
          this.user = data.response.user;
          this.trading_info = data.response.trading_info;
          this.form.group = this.user.group;
        }
      })
      .catch(() => {
        Vue.notify({
          group: "signals",
          type: "error",
          text: "Can't load information!",
        });
      })
      .finally(() => {
        this.loading = false;
        Nprogress.done();
      });
    let instance = axios.create();
    delete instance.defaults.headers.common["Authorization"];
    instance
      .get(`${webServices.countryListURL}`, {
        headers: { "Content-Type": "application/json" },
      })
      .then(({ data }) => {
        this.countryList = data.map((list, index) => ({
          index,
          name: list.name,
          flag: list.flag,
        }));
      });
  },
  methods: {
    ...mapActions(["getGroups"]),
    getDateFormat(date) {
      if (!date) return "";
      return dateformat(new Date(date), "mmm, dd yyyy HH:MM");
    },
    getDateFormatBirth(date) {
      if (!date) return "";
      return dateformat(new Date(date), "mmm, dd yyyy");
    },
    getDateFormatWithMS(date) {
      if (!date) return "";
      date = parseInt(date);
      return dateformat(date, "mmm, dd yyyy HH:MM");
    },
    onDeleteUser() {
      this.$refs.deleteConfirmationDialog.openDialog();
    },
    deleteUser() {
      let _this = this;
      Nprogress.start();
      axios
        .delete(`${webServices.baseURL}/users/${this.user_id}`)
        .then(() => {
          Vue.notify({
            group: "signals",
            type: "success",
            text: "User deleted!",
          });
          setTimeout(() => {
            _this.$router.push("/users-list");
          }, 1500);
        })
        .catch(() => {
          Vue.notify({
            group: "signals",
            type: "error",
            text: "Deletion failed!",
          });
        })
        .finally(() => {
          this.$refs.deleteConfirmationDialog.close();
          Nprogress.done();
        });
    },
    onEditUser() {
      this.openDialog();
    },
    editUser() {
      if (this.$refs.form.validate()) {
        const {
          name,
          email,
          phone,
          password,
          country,
          address,
          zipcode,
          group,
        } = this.form;
        let userdata = {
          name,
          email,
        };
        if (phone && phone != "") {
          userdata = { ...userdata, phone };
        }
        if (address && address != "") {
          userdata = { ...userdata, address };
        }
        if (country && country != "") {
          userdata = {
            ...userdata,
            country: this.countryList[country].name,
            flag: this.countryList[country].flag,
          };
        }
        if (zipcode && zipcode != "") {
          userdata = { ...userdata, zipcode };
        }
        if (password && password != "") {
          userdata = { ...userdata, password };
        }
        if (group && group != "") {
          userdata = { ...userdata, group };
        }
        Nprogress.start();
        axios
          .patch(`${webServices.baseURL}/users/${this.user_id}`, userdata)
          .then(() => {
            this.user = {
              ...this.user,
              ...userdata,
            };
            Vue.notify({
              group: "signals",
              type: "success",
              text: "Profile Updated!",
            });
          })
          .catch(() => {
            Vue.notify({
              group: "signals",
              type: "error",
              text: "Updated failed",
            });
          })
          .finally(() => {
            this.open = false;
            Nprogress.done();
          });
      }
    },
    changeAvatar(event) {
      var files = event.target.files || event.dataTransfer.files;
      if (!files.length) return;
      var formData = new FormData();
      formData.append("avatar", files[0]);
      Nprogress.start();
      axios
        .post(`${webServices.baseURL}/users/avatar/${this.user_id}`, formData, {
          headers: {
            "Content-Type": "multipart/form-data",
          },
        })
        .then(({ data }) => {
          Vue.notify({
            group: "signals",
            type: "success",
            text: "Successfully Updated!",
          });
          this.user.avatar = data.response.filename;
        })
        .catch(() => {
          Vue.notify({
            group: "signals",
            type: "error",
            text: "Upload failed",
          });
        })
        .finally(() => {
          Nprogress.done();
        });
    },
    openDialog() {
      this.form.name = this.user.name;
      this.form.email = this.user.email;
      this.form.address = this.user.address;
      this.form.zipcode = this.user.zipcode;
      this.form.country = this.countryList.findIndex(
        (list) => list.name === this.user.country
      );
      this.form.country = this.form.country >= 0 ? this.form.country : "";
      this.form.phone = this.user.phone;
      this.form.password = this.user.password;
      this.form.group = this.user.group.toString();

      this.open = true;
    },
  },
  computed: {
    ...mapGetters(["groups"]),

    groupItems() {
      return this.groups.map((group) => ({
        value: group.id.toString(),
        text: group.name,
      }));
    },
    groupLabel() {
      let groupItem = this.groupItems.filter(
        (item) => item.value == this.user.group
      );
      if (groupItem.length) {
        return groupItem[0].text;
      }
      return "";
    },
  },
  watch: {},
};
</script>