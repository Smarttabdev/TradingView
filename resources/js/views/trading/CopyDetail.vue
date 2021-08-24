<template>
  <div class="copy-details-view">
    <page-title-bar></page-title-bar>
    <app-section-loader :status="copyDetail_loading"></app-section-loader>
    <v-container fluid class="grid-list-xl pt-0 mt-n3">
      <v-row>
        <app-card
          :fullBlock="true"
          colClasses="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"
        >
          <app-card class="title-bar" v-if="copyDetail_information">
            <h4 class="text-capitalize mb-0">
              Provider:&nbsp;<b>{{ copyDetail_information.provider }}</b>
              &nbsp;&nbsp;&nbsp;&nbsp; Source Account:&nbsp;<b>{{
                copyDetail_information.account_number
              }}</b>
              &nbsp;&nbsp;&nbsp;&nbsp; Broker:&nbsp;<b>{{
                copyDetail_information.broker
              }}</b>
              &nbsp;&nbsp;&nbsp;&nbsp;
            </h4>
          </app-card>
          <v-data-table
            :headers="headers"
            :items="copyDetail_data"
            :search="search"
            :mobile-breakpoint="0"
            item-key="email"
            :server-items-length="copyDetail_total"
            :options.sync="options"
            :loading="copyDetail_loading"
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
                <!-- <td>{{ getDateFormat(props.item.created_at) }}</td> -->
                <td>
                  <div class="user-info">
                    <img
                      :src="
                        props.item.avatar
                          ? props.item.avatar
                          : '/static/avatars/default.png'
                      "
                      alt="avatar"
                      height="40"
                      width="40"
                      class="img-avatar"
                    />
                    <div>
                      {{ props.item.name }} <br />
                      <v-tooltip right>
                        <template v-slot:activator="{ on }">
                          <img v-on="on" :src="props.item.flag" width="30" />
                        </template>
                        <span>{{ props.item.country }}</span>
                      </v-tooltip>
                    </div>
                  </div>
                </td>
                <!-- <td style="text-transform: uppercase">{{ props.item.type }}</td> -->
                <td>{{ props.item.broker }}</td>
                <td>{{ props.item.account_number }}</td>
                <!-- <td>{{ props.item.takeProfitPrice }}</td> -->
                <!-- <td>{{ props.item.stopLossPrice }}</td> -->
                <!-- <td>{{ props.item.ticket }}</td> -->
              </tr>
            </template>
          </v-data-table>
        </app-card>
      </v-row>
    </v-container>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";
import dateformat from "dateformat";
export default {
  data() {
    return {
      search: "",
      headers: [
        {
          text: "#",
          align: "left",
          sortable: false,
        },
        { text: "User", sortable: false },
        { text: "Broker", sortable: false },
        { text: "Source Account", sortable: false },
      ],
      options: {},
    };
  },
  mounted() {},
  methods: {
    ...mapActions(["copyDetailAction"]),
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
    },
  },
  computed: {
    ...mapGetters([
      "copyDetail_data",
      "copyDetail_perPage",
      "copyDetail_total",
      "copyDetail_page",
      "copyDetail_information",
      "copyDetail_loading",
    ]),
  },

  watch: {
    options: function (options) {
      this.copyDetailAction({
        page: options.page,
        perPage: options.itemsPerPage,
        account_number: this.$route.params.account_number,
        broker: this.$route.params.broker,
      });
    },
  },
};
</script>