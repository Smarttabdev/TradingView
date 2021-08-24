<template>
  <div
    class="
      app-footer
      Vuely-toolbar
      d-custom-flex
      justify-space-between
      align-items-center
      py-4
      pr-5
    "
    :class="activeHeaderFilter.class"
  >
    <ul class="list-inline footer-menu mb-0">
      <li>
        <!-- $t("message.termsOfService") -->
        <v-btn text @click="open = true"> Disclaimer </v-btn>
      </li>
      <!-- <li>
        <v-btn
          text
          :to="`/${getCurrentAppLayoutHandler() + '/pages/pricing-1'}`"
          >{{ $t("message.pricing") }}</v-btn
        >
      </li>
      <li>
        <v-btn
          text
          :to="`/${getCurrentAppLayoutHandler() + '/pages/gallery'}`"
          >{{ $t("message.gallery") }}</v-btn
        >
      </li>
      <li>
        <v-btn text :to="`/${getCurrentAppLayoutHandler() + '/inbox'}`">{{
          $t("message.inbox")
        }}</v-btn>
      </li>
      <li>
        <v-btn
          text
          :to="`/${getCurrentAppLayoutHandler() + '/ecommerce/shop'}`"
          >{{ $t("message.shop") }}</v-btn
        >
      </li>  -->
    </ul>
    <h6 class="mb-0 fw-normal">{{ copyrightText }}</h6>
    <template>
      <v-dialog v-model="open" max-width="650px">
        <v-card>
          <v-card-title class="headline grey lighten-2">
            Disclaimer
          </v-card-title>

          <v-card-text>
            <br />
            <br />
            Forex and CFD trading can be extremely risky and it is possible to
            lose all of your investment.<br />Past performance is not
            necessarily indicative of future growth.<br />
            Please be aware of the risks and ensure you understand the product,
            leverage, and the strategy you are investing in.<br />
            Social trading and investing are not suitable for everyone.<br />
            There is never a “guaranteed” performance.<br />
            Some managers / trade leaders will be vastly more experienced than
            others.<br />
            Some managers will trade off a demo account and not a live
            account.<br />
            Some results will be hypothetical.<br />
            Your trading results may differ from that of the manager.<br />
            There are fees and subscription costs, and we advise you make
            yourself aware of these before commencing any trading.<br />
            We are a Technology provider and as such we do not advise on trading
            or recommend any strategy.<br />
            We are not accountable for any investment decisions you may make.<br />
            We cannot say whether any strategy or manager is suitable for
            you.<br />
            The trade copier is a technology solution and social trading tool
            and as such we may experience downtime as may your broker.<br />
            We are not responsible for any losses that may occur as a result of
            this.<br />
          </v-card-text>

          <v-divider></v-divider>

          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn color="error" @click="open = false"> Close </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </template>
  </div>
</template>

<script>
import { mapGetters } from "vuex";
import AppConfig from "Constants/AppConfig";
import { getCurrentAppLayout } from "Helpers/helpers";

export default {
  components: {},
  data() {
    return {
      copyrightText: AppConfig.copyrightText,
      open: false,
    };
  },
  methods: {
    getCurrentAppLayoutHandler() {
      return getCurrentAppLayout(this.$router);
    },
  },
  computed: {
    ...mapGetters(["activeHeaderFilter"]),
  },
};
</script>
