<template>
  <v-dialog persistent v-model="open" max-width="850px">
    <v-card>
      <v-card-title class="headline grey lighten-2">
        Terms of Service
      </v-card-title>

      <v-card-text>
        <br />
        <br />
        Trading FX, CFDs and any Investment can be risky. Ensure you fully
        understand the risks involved in trading. You can lose money. FX and CFD
        trading are not appropriate for everyone.
        <br />
        <br />
        We make no recommendation to follow any strategy provider advertised
        within this website. We are a technology company and do not offer
        financial advice in any way.
        <br />
        <br />
        Some trading results may be hypothetical and past performance is not
        indicative of future returns. There is no assumption or guarantee that
        any account will achieve past historical profit or loss. Some strategy
        providers will trade from a demo account using high leverage. There will
        be differences on some trading accounts due to different pricing at
        different liquidity providers.
        <br />
        <br />
        All risk decisions and all portfolio decision making are entirely down
        to the individual.
        <br />
        <br />
        Subscription fees are fully disclosed. Some brokers may charge
        commissions on the trading accounts.
        <br />
        <br />
        As a Technology Provider we provide links to Strategy Providers and
        Liquidity Providers we do not facilitate transactions ourselves. All
        executed trades and transactions are facilitated by the Strategy
        Provider and Liquidity Provider adhering to their own Terms and
        Conditions, which we strongly advise each user to read.
        <br />
        <br />
        Our full Terms and Conditions and Risk Warning is available within our
        website.
        <br />
        <br />
        <!-- “Some results maybe hypothetical. Past results are not indicative of
        future performance. Trading FX and CFDs is high risk and trading
        leveraged products can result in losses that exceed the value of your
        account. Please ensure that such an investment is suitable for you and
        that you fully understand the risks involved before starting, as trading
        FX and CFDs is not suitable for everyone. We make no recommendation to
        follow, copy or trade any strategy or manager. Indeed, by following any
        strategy the performance can also vary due to differences in pricing at
        different brokers or on different accounts. We are a technology provider
        and marketplace whereby technology can and will experience downtime. We
        assume no responsibility for any such unavoidable downtime, or any
        losses howsoever occurred.” -->
      </v-card-text>

      <v-divider></v-divider>

      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn color="error" @click="open = false"> Got it </v-btn>
      </v-card-actions>
    </v-card>
  </v-dialog>
</template>

<script>
export default {
  name: "terms-of-service",
  data() {
    return {
      open: false,
    };
  },
  props: {
    isOpen: Boolean,
  },
  watch: {
    isOpen(newValue) {
      this.open = newValue;
    },
    open(newValue) {
      if (!newValue) this.$emit("close");
    },
  },
};
</script>