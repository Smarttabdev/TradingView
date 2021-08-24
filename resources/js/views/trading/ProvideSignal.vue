<template>
  <div>
    <page-title-bar></page-title-bar>
    <app-section-loader :status="provideSignal_loading"></app-section-loader>
    <v-container fluid class="grid-list-xl pt-0 mt-n3">
      <v-row>
        <app-card
          :fullBlock="true"
          colClasses="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
        >
          <v-row class="align-items-center search-wrap">
            <v-col cols="12" md="12" lg="12" class="align-items-center pt-0">
              <app-card customClasses="mb-0 pt-8">
                <v-row>
                  <v-col cols="12" md="12" lg="12" class="pb-0">
                    <div class="d-flex">
                      <div class="w-75">
                        <v-text-field
                          class="pt-0 pr-3"
                          label="Search Signal"
                          v-model="search"
                        >
                        </v-text-field>
                      </div>
                      <div>
                        <v-btn
                          color="primary"
                          class="my-0 ml-0 mr-2"
                          medium
                          @click="handleSearch"
                          ><i class="material-icons">search</i
                          >&nbsp;&nbsp;Search</v-btn
                        >
                        <v-btn
                          color="primary m-0"
                          medium
                          @click="provideModal = true"
                          v-if="getUser.group.canProvide"
                          ><i class="material-icons">add</i
                          >&nbsp;&nbsp;Add</v-btn
                        >
                      </div>
                    </div>
                  </v-col>
                </v-row>
              </app-card>
            </v-col>
          </v-row>
          <v-data-table
            :mobile-breakpoint="0"
            :key="tableProvideKey"
            :headers="headers"
            :items="provideSignal_data"
            :search="search"
            item-key="email"
            :server-items-length="provideSignal_total"
            :options.sync="options"
            :loading="provideSignal_loading"
            :footer-props="{
              showFirstLastPage: true,
              itemsPerPageOptions: [5, 10, 15, 20],
            }"
          >
            <template
              slot="headerCell"
              slot-scope="props"
              :loading-text="'Loading... Please wait'"
            >
              <v-tooltip bottom>
                <span slot="activator">
                  {{ props.header.text }}
                </span>
                <span>
                  {{ props.header.text }}
                </span>
              </v-tooltip>
            </template>
            <template v-slot:item="props">
              <tr>
                <td>{{ props.index + 1 }}</td>
                <td>{{ props.item.strategy_name }}</td>
                <td>{{ props.item.trading_style }}</td>
                <td>
                  <v-tooltip bottom v-if="props.item.description">
                    <template v-slot:activator="{ on }">
                      <div
                        v-on="on"
                        @click="
                          descriptionShowMore(
                            props.item.description,
                            props.item.strategy_name
                          )
                        "
                        class="truncate"
                        style="cursor: pointer"
                      >
                        {{ props.item.description }}
                      </div>
                    </template>
                    <span>Read more</span>
                  </v-tooltip>
                </td>
                <td>{{ props.item.broker }}</td>
                <td>{{ props.item.account_number }}</td>
                <td>{{ props.item.subscription_fee }}</td>
                <td>{{ props.item.performance_fee }}</td>
                <td>{{ props.item.signal_number }}</td>
                <td>{{ getDateFormatWithMS(props.item.openTime) }}</td>
                <td>
                  <v-btn
                    text
                    color="primary"
                    @click="
                      showCopiers(props.item.account_number, props.item.broker)
                    "
                  >
                    {{ props.item.copier_number }}
                  </v-btn>
                </td>
                <td>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        v-on="on"
                        text
                        icon
                        color="primary"
                        @click="
                          gotoDetail(
                            props.item.account_number,
                            props.item.broker
                          )
                        "
                      >
                        <v-icon class="zmdi zmdi-eye"></v-icon>
                      </v-btn>
                    </template>
                    <span>View Source Detail</span>
                  </v-tooltip>
                  <v-tooltip bottom>
                    <template v-slot:activator="{ on }">
                      <v-btn
                        v-on="on"
                        text
                        icon
                        color="error"
                        @click="deleteSource(props.item.id)"
                      >
                        <v-icon class="zmdi zmdi-delete"></v-icon>
                      </v-btn>
                    </template>
                    <span>Cancel Providing This Source</span>
                  </v-tooltip>
                </td>
              </tr>
            </template>
          </v-data-table>
        </app-card>
      </v-row>
    </v-container>
    <delete-confirmation-dialog
      ref="deleteConfirmationDialog"
      heading="Are You Sure You Want To Delete?"
      message="Are you sure you want to delete this Source permanently?"
      @onConfirm="deleteSourceConfirm"
    >
    </delete-confirmation-dialog>
    <template>
      <v-dialog v-model="provideModal" max-width="600">
        <v-card class="pa-6">
          <v-form v-model="form.valid" ref="form" lazy-validation>
            <h2>
              Please select source account number to provide signal source.
            </h2>
            <v-select
              class="mb-3"
              hide-details
              v-bind:items="accounts"
              v-model="form.account"
              :rules="form.accountRules"
              label="Select Account"
              required
            >
            </v-select>
            <v-text-field
              label="Strategy Name"
              v-model="form.strategyName"
            ></v-text-field>
            <v-select
              class="mb-10 mt-0 pt-0"
              hide-details
              v-bind:items="tradingStyle"
              v-model="form.tradingStyle"
              label="Trading Style"
            >
            </v-select>
            <v-slider
              min="30"
              max="250"
              v-model="form.subscriptionFee"
              thumb-label="always"
              :thumb-size="30"
              label="Subscription Fee"
            >
              <template v-slot:thumb-label="{ value }"> {{ value }}£ </template>
            </v-slider>
            <v-slider
              min="15"
              max="30"
              v-model="form.performanceFee"
              thumb-label="always"
              :thumb-size="30"
              label="Performance Fee"
            >
              <template v-slot:thumb-label="{ value }"> {{ value }}% </template>
            </v-slider>
            <v-text-field
              type="number"
              label="Suggested Minimun Account Size"
              v-model="form.miniSize"
            ></v-text-field>
            <v-textarea
              label="Description"
              v-model="form.description"
              class="mb-3 pt-0"
            ></v-textarea>
            <v-btn
              @click="provideSource"
              :disabled="!form.valid"
              color="success"
              class="mr-3"
            >
              Add
            </v-btn>
            <v-btn color="primary" @click="provideModal = false" class="px-4"
              >Cancel</v-btn
            >
          </v-form>
        </v-card>
      </v-dialog>
    </template>
    <template>
      <v-dialog v-model="descriptionDialog" max-width="600px">
        <v-card>
          <v-card-title class="headline grey lighten-2">
            Description - {{ strategy_name }}
          </v-card-title>

          <v-card-text>
            <br />
            {{ description }}
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="error" @click="descriptionDialog = false">
              Close
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </template>
    <confirmation-dialog
      ref="provideConfirmationDialog"
      heading="Are You Sure You Want To Provide?"
      message="Are you sure you want to provide this Account?"
      @onConfirm="provideConfirm"
      confirmColor="success"
    >
    </confirmation-dialog>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import dateformat from "dateformat";
import axios from "axios";
import webServices from "WebServices";
import Vue from "vue";

export default {
  data() {
    return {
      search: "",
      description: "",
      strategy_name: "",
      descriptionDialog: false,
      headers: [
        {
          text: "#",
          align: "left",
          sortable: false,
        },
        { text: "Strategy Name", sortable: false },
        { text: "Trading Style", sortable: false },
        { text: "Description", sortable: false },
        { text: "Broker", sortable: false },
        { text: "Source Account", sortable: false },
        { text: "Subscription Fee", sortable: false },
        { text: "Performance Fee", sortable: false },
        { text: "Number of Signals", sortable: false },
        { text: "Since", sortable: false },
        { text: "Number Of Copiers", sortable: false },
        { text: "", sortable: false },
      ],
      options: {},
      delete_id: null,
      tableProvideKey: 0,
      provideModal: false,
      form: {
        valid: true,
        account: null,
        description: "",
        subscriptionFee: 30,
        performanceFee: 15,
        accountRules: [(v) => !!v || "Please choose an account."],
        strategyName: "",
        tradingStyle: "",
        miniSize: 0,
      },
      accounts: [],
      tradingStyle: [
        "Trend following",
        "Breakout",
        "High Frequency",
        "Discretionary",
        "Systematic",
        "Intraday",
        "Mean Reversion",
        "Fundamental",
      ],
    };
  },
  mounted() {
    axios
      .get(`${webServices.baseURL}/accounts-for-provide`, {
        headers: { "Content-Type": "application/json" },
      })
      .then(({ data }) => {
        const { api_status, accounts } = data.response;
        if (api_status) {
          this.accounts = accounts.map((account) => {
            return {
              text: account.account_number + " (" + account.broker + ")",
              value: account,
            };
          });
        }
      })
      .catch(() => {
        Vue.notify({
          group: "signals",
          type: "error",
          text: "Loading accounts failed!",
        });
      });
  },
  methods: {
    ...mapActions(["getProvideSignalAction"]),
    ...{
      getDateFormat(date) {
        if (!date) return "";
        return dateformat(new Date(date), "mmm, dd yyyy HH:MM");
      },
      getDateFormatWithMS(date) {
        if (!date) return "";
        date = parseInt(date);
        return dateformat(date, "mmm, dd yyyy HH:MM");
      },
      gotoDetail(account_number, broker) {
        this.$router.push({
          path: `signal-detail/${account_number}/${broker}`,
        });
      },
      showCopiers(account_number, broker) {
        this.$router.push({
          path: `copy-detail/${account_number}/${broker}`,
        });
      },
      deleteSource(id) {
        this.delete_id = id;
        this.$refs.deleteConfirmationDialog.openDialog();
      },
      deleteSourceConfirm() {
        axios
          .delete(`${webServices.baseURL}/providesource/${this.delete_id}`)
          .then(({ data }) => {
            const { api_status, message } = data.response;
            if (api_status) {
              this.tableProvideKey++;
              Vue.notify({
                group: "signals",
                type: "success",
                text: "Delete success!",
              });
            } else {
              Vue.notify({
                group: "signals",
                type: "error",
                text: message,
              });
            }
          })
          .catch((error) => {
            let message = "Delete signal failed.";
            if (error.response) {
              const { response } = error.response.data;
              message = response.message;
            }
            Vue.notify({
              group: "signals",
              type: "error",
              text: message,
            });
          })
          .finally(() => {
            this.$refs.deleteConfirmationDialog.close();
          });
      },
      provideSource() {
        if (!this.form.account) return;
        this.provideModal = false;
        this.$refs.provideConfirmationDialog.openDialog();
      },
      provideConfirm() {
        axios
          .post(
            `${webServices.baseURL}/providesources`,
            {
              ...this.form.account,
              strategyName: this.form.strategyName,
              description: this.form.description,
              subscriptionFee: this.form.subscriptionFee,
              performanceFee: this.form.performanceFee,
              tradingStyle: this.form.tradingStyle,
              miniSize: this.form.miniSize,
            },
            {
              headers: { "Content-Type": "application/json" },
            }
          )
          .then(({ data }) => {
            const { api_status, message } = data.response;
            if (api_status) {
              this.tableProvideKey++;
              Vue.notify({
                group: "signals",
                type: "success",
                text: "Provide signal success!",
              });
            } else {
              Vue.notify({
                group: "signals",
                type: "error",
                text: message,
              });
            }
          })
          .catch((error) => {
            let message = "Provide signal failed.";
            if (error.response) {
              const { response } = error.response.data;
              message = response.message;
            }
            Vue.notify({
              group: "signals",
              type: "error",
              text: message,
            });
          })
          .finally(() => {
            this.form.account = null;
            this.$refs.provideConfirmationDialog.close();
          });
      },
      handleSearch() {
        this.getProvideSignalAction({
          page: this.options.page,
          perPage: this.options.itemsPerPage,
          search: this.search,
        });
      },
    },
    descriptionShowMore(description, strategy_name) {
      this.description = description;
      this.strategy_name = strategy_name;
      this.descriptionDialog = true;
    },
  },
  computed: {
    ...mapGetters([
      "provideSignal_data",
      "provideSignal_perPage",
      "provideSignal_total",
      "provideSignal_page",
      "provideSignal_loading",
      "getUser",
    ]),
  },

  watch: {
    options: function (options) {
      this.getProvideSignalAction({
        page: options.page,
        perPage: options.itemsPerPage,
        search: this.search,
      });
    },
  },
};
</script>