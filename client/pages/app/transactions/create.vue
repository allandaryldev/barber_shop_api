<template>
  <div class="pt-4 shadow-2xl">
    <Card class="rounded-lg">
      <template #header>
        <div class="px-2">
          <PageTitle page_title="New Transaaction">
            <div class="flex justify-end" v-if="nextStep">
              <vs-button danger border @click="handleRemoveCustomer">
                Reset Customer
              </vs-button>
              <vs-button warn border @click="resetItems">
                Reset Services
              </vs-button>
              <vs-button
                :loading="loading"
                success
                border
                @click="handleSubmitTransaction"
              >
                Submit Transaction
              </vs-button>
            </div>
          </PageTitle>
        </div>
      </template>

      <template #content>
        <ValidationObserver v-slot="{}" ref="transactionform">
          <div class="grid grid-cols-1 md:grid-cols-2 md:gap-6">
            <div>
              <div class="w-full bg-gray-100 rounded-lg">
                <div class="p-2">
                  <div v-if="Object.keys(selected_customer).length == 0">
                    <ValidationProvider
                      vid="customer"
                      name="Customer"
                      rules="required"
                      v-slot="{ errors }"
                    >
                      <b-field
                        label="Customer"
                        label-position="inside"
                        :type="{ 'is-danger': errors[0] }"
                        :message="errors[0]"
                      >
                        <b-autocomplete
                          v-model="search_customers"
                          :data="filtered_customer"
                          placeholder="Search Customer"
                          field="name"
                          :loading="searching_customers"
                          @select="
                            (customer) =>
                              handleSelectedCustomer(customer, false)
                          "
                        >
                          <template slot-scope="props">
                            <div class="flex items-center border-b-2">
                              <div>
                                <Avatar shape="circle">
                                  <svg
                                    class="h-8 w-8"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                  >
                                    <path
                                      stroke-linecap="round"
                                      stroke-linejoin="round"
                                      stroke-width="2"
                                      d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                  </svg>
                                </Avatar>
                              </div>
                              <div class="px-2 capitalize">
                                {{ props.option.name }}
                              </div>
                            </div>
                          </template>

                          <template #footer>
                            <vs-button
                              border
                              class="w-full"
                              @click="handleAddCustomerModal"
                              >Add New</vs-button
                            >
                          </template>

                          <template #empty>
                            <div>No Data Found</div>
                          </template>
                        </b-autocomplete>
                      </b-field>
                    </ValidationProvider>
                  </div>
                  <div v-else>
                    <div class="space-y-4">
                      <b-collapse
                        class="card"
                        animation="slide"
                        aria-id="contentIdForA11y3"
                      >
                        <template #trigger="props">
                          <div
                            class="
                              card-header
                              flex
                              items-center
                              justify-between
                              p-2
                            "
                            role="button"
                            aria-controls="contentIdForA11y3"
                          >
                            <div
                              v-if="!nextStep"
                              class="flex justify-start items-center space-x-2"
                            >
                              <vs-button
                                border
                                warn
                                @click="handleRemoveCustomer"
                                >Select Another</vs-button
                              >
                              <vs-button
                                border
                                @click="handleNextStep"
                                class="hover:animate-bounce"
                                >Next</vs-button
                              >
                            </div>
                            <div v-else>
                              <p class="">Customer</p>
                            </div>
                            <!-- <p v-if="!nextStep">
                                <div class="flex">
                                  <vs-button
                                    class="w-full"
                                    border
                                    warn
                                    @click="handleRemoveCustomer"
                                  >
                                    Select Another 
                                  </vs-button>
                                  <vs-button
                                    border
                                    class="w-full"
                                    @click="handleNextStep"
                                  >
                                    Next
                                  </vs-button>
                                </div>
                              </p> -->
                            <a class="">
                              <b-icon
                                :icon="props.open ? 'menu-down' : 'menu-up'"
                              >
                              </b-icon>
                            </a>
                          </div>
                        </template>

                        <div class="">
                          <Card class="">
                            <template #title>
                              <div class="flex justify-between items-center">
                                <svg
                                  xmlns="http://www.w3.org/2000/svg"
                                  class="h-20 w-20"
                                  fill="none"
                                  viewBox="0 0 24 24"
                                  stroke="currentColor"
                                >
                                  <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"
                                  />
                                </svg>

                                <vue-qr
                                  class="h-20 w-20"
                                  :text="selected_customer.name"
                                  :size="200"
                                ></vue-qr>
                              </div>
                              <div class="capitalize">
                                {{ selected_customer.name }}
                              </div>
                            </template>
                            <template #content>
                              <div class="text-lg font-bold">
                                <span class="text-sm font-normal"
                                  >Mobile:
                                </span>
                                {{ selected_customer.mobile_no }}
                              </div>

                              <div class="text-lg font-bold">
                                <span class="text-sm font-normal"
                                  >Current Points:
                                </span>
                                {{ selected_customer.points }}
                              </div>
                            </template>
                            <template #footer v-if="!nextStep"> </template>
                          </Card>
                        </div>
                      </b-collapse>
                      <b-collapse
                        class="card"
                        animation="slide"
                        aria-id="contentIdForA11y3"
                        v-if="nextStep"
                      >
                        <template #trigger="props">
                          <div
                            class="
                              card-header
                              flex
                              items-center
                              justify-between
                              p-2
                            "
                            role="button"
                            aria-controls="contentIdForA11y3"
                          >
                            <p class="">Transaction Items</p>
                            <a class="">
                              <b-icon
                                :icon="props.open ? 'menu-down' : 'menu-up'"
                              >
                              </b-icon>
                            </a>
                          </div>
                        </template>
                        <div>
                          <div class="p-2">
                            <div class="mt-1">
                              <ValidationProvider
                                vid="services"
                                name="Services"
                                rules=""
                                v-slot="{ errors }"
                              >
                                <b-field
                                  label="Services"
                                  :type="{ 'is-danger': errors[0] }"
                                  :message="errors[0]"
                                  horizontal
                                >
                                  <b-autocomplete
                                    v-model="search_services"
                                    :data="filtered_services"
                                    placeholder="Search Services"
                                    field="name"
                                    :loading="searching_services"
                                    @select="
                                      (service) => showService(service, false)
                                    "
                                  >
                                    <template slot-scope="props">
                                      <div class="capitalize">
                                        {{ props.option.name }}
                                      </div>
                                    </template>

                                    <template #footer>
                                      <vs-button
                                        border
                                        class="w-full"
                                        @click="handleAddServiceModal"
                                        >Add New</vs-button
                                      >
                                    </template>
                                    <template #empty>
                                      <div>No Data Found</div>
                                    </template>
                                  </b-autocomplete>
                                </b-field>
                              </ValidationProvider>
                            </div>
                            <div class="mt-1">
                              <ValidationProvider
                                vid="qty"
                                name="Item Quantity"
                                rules="required"
                                v-slot="{ errors }"
                              >
                              ``
                                <b-field
                                  label="Quantity"
                                  :type="{ 'is-danger': errors[0] }"
                                  :message="errors[0]"
                                  horizontal
                                >
                                <b-numberinput v-model="items_qty" controls-position="compact"  expanded></b-numberinput>
                                </b-field>
                              </ValidationProvider>
                            </div>
                            <div>
                              <div class="mt-1">
                                <div class="w-full">
                                  <div class="grid grid-cols-5 gap-6">
                                    <div
                                      class="
                                        col-span-1
                                        text-center
                                        flex
                                        justify-center
                                        items-center
                                      "
                                    >
                                      <span class="text-md font-bold"
                                        >Cost:</span
                                      >
                                    </div>
                                    <div
                                      class="col-span-4 bg-white p-2 rounded-lg"
                                    >
                                      {{
                                        $moneyFormat.format(
                                          setItemService.data.value
                                            ? setItemService.data.value
                                            : 0
                                        )
                                      }}
                                    </div>
                                  </div>
                                  <div class="mt-1 grid grid-cols-5 gap-6">
                                    <div
                                      class="
                                        col-span-1
                                        text-center
                                        flex
                                        justify-center
                                        items-center
                                      "
                                    >
                                      <span class="text-md font-bold"
                                        >Total:</span
                                      >
                                    </div>
                                    <div
                                      class="col-span-4 bg-white p-2 rounded-lg"
                                    >
                                      <span v-if="setItemService.data.value">
                                        {{
                                          $moneyFormat.format(
                                            setItemService.data.value *
                                              items_qty
                                          )
                                        }}
                                      </span>
                                      <span v-else>
                                        {{ $moneyFormat.format(0) }}
                                      </span>
                                    </div>
                                  </div>
                                </div>
                                <div class="flex justify-end">
                                  <vs-button
                                    @click="handleSelectService"
                                    class=""
                                    dark
                                    border
                                  >
                                    Add Item
                                  </vs-button>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </b-collapse>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div>
              <!-- <div class="w-full h-56 bg-gray-200" v-if="nextStep"> -->
              <div class="w-full bg-gray-100 rounded-lg">
                <div class="p-2">
                  <div v-if="transaction_field.items.length">
                    <v-list
                      shaped
                      two-line
                      class="border border-black max-h-96 overflow-y-auto"
                    >
                      <v-subheader class="flex justify-between items-center">
                        <div>Added Items</div>
                        <div>
                          Grand:
                          <span class="font-semibold text-base">{{
                            $moneyFormat.format(grand_total)
                          }}</span>
                        </div>
                      </v-subheader>
                      <v-list-item
                        v-for="(item, i) in transaction_field.items"
                        :key="i"
                      >
                        <v-list-item-icon>
                          {{ i + 1 }}
                        </v-list-item-icon>
                        <v-list-item-content>
                          <v-list-item-title
                            class="capitalize font-semibold"
                            v-text="item.data.name"
                          ></v-list-item-title>
                          <v-list-item-subtitle>
                            <div
                              class="flex justify-between items-center text-sm"
                            >
                              <p>
                                Qty:
                                <span class="font-semibold">{{
                                  item.qty
                                }}</span>
                              </p>
                              <p>
                                Cost:
                                <span class="font-semibold">{{
                                  $moneyFormat.format(item.data.value)
                                }}</span>
                              </p>
                              <p>
                                Total:
                                <span class="font-semibold">{{
                                  $moneyFormat.format(
                                    item.qty * item.data.value
                                  )
                                }}</span>
                              </p>
                            </div>
                          </v-list-item-subtitle>
                          <v-divider class="p-0 m-0"></v-divider>
                        </v-list-item-content>
                        <v-list-item-icon>
                          <svg
                            @click="removeItem(i)"
                            class="h-5 w-5 text-red-500"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            stroke-width="2"
                            stroke="currentColor"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                          >
                            <path stroke="none" d="M0 0h24v24H0z" />
                            <line x1="18" y1="6" x2="6" y2="18" />
                            <line x1="6" y1="6" x2="18" y2="18" />
                          </svg>
                        </v-list-item-icon>
                      </v-list-item>
                    </v-list>
                  </div>
                  <template v-else>
                    <div
                      class="text-center text-gray-400 text-lg font-semibold"
                    >
                      Please Add Atleast One Service
                    </div>
                  </template>
                </div>
              </div>
            </div>
          </div>
        </ValidationObserver>
      </template>
    </Card>

    <AddCustomer
      :modal="addCustomerModal"
      :close="handleAddCustomerModal"
      :get="getCustomers"
    />
    <ServiceAdd
      :modal="addServiceModal"
      :close="handleAddServiceModal"
      :get="getServices"
    />
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  data() {
    return {
      confirmModal: false,
      transaction_field: {
        customer_id: '',
        useReward: false,
        items: [],
        total: 0,
      },
      filtered_customer: [],
      search_customers: '',
      searching_customers: false,
      filtered_services: [],
      search_services: '',
      searching_services: false,
      selected_customer: {},
      setItemService: {
        active: false,
        data: {},
        qty: 1,
      },
      items_qty: 1,

      nextStep: false,
      addCustomerModal: false,
      addServiceModal: false,
      loading: false,
    }
  },
  watch: {
    search_customers(value) {
      setTimeout(() => {
        this.filter_customers = value
      }, 1000)
    },
    search_services(value) {
      setTimeout(() => {
        this.filter_services = value
      }, 1000)
    },
    confirmModal(value) {
      if (value) {
        this.$buefy.dialog.confirm({
          type: 'is-success',
          "aria-role":'alertdialog',
          "trap-focus":true, 
          focusOn:"confirm",
          hasIcon:true,
          title:"Transaction Success ",
          message: 'Create another transaction?',
          confirmText:"Yes",
          cancelText:"No",
          onConfirm: () => {
            this.handleConfirmModal()
            this.clearAll();
          },
          onCancel:async ()=> {
            await this.handleConfirmModal()
            this.$router.push('/app/transactions')
          }
        })
      }
    },
  },
  computed: {
    ...mapGetters({
      customers: 'customers/customers',
      services: 'services/services',
    }),
    grand_total() {
      let totals = []
      this.transaction_field.items.forEach(({ data, qty }) => {
        const total = qty * data.value
        totals.push(total)
      })
      return totals.reduce((a, b) => {
        return a + b
      })
    },
    filter_customers: {
      get() {
        return this.filtered_customer
      },
      set(data) {
        let customers_data = [...this.customers.data]
        this.searching_customers = true
        let filtered = customers_data.filter((value) => {
          if (!data) return ''
          return value.name.toLowerCase().includes(data.toLowerCase())
        })

        this.filtered_customer = [...filtered]
        this.searching_customers = false
      },
    },
    filter_services: {
      get() {
        return this.filtered_services
      },
      set(data) {
        let services_data = [...this.services.data]
        this.searching_services = true
        let filtered = services_data.filter((value) => {
          if (!data) return ''
          return value.name.toLowerCase().includes(data.toLowerCase())
        })

        this.filtered_services = [...filtered]
        this.searching_services = false
      },
    },
  },
  mounted() {
    Promise.all([this.getCustomers(), this.getServices()])
    // console.log(this.$vs);
  },
  methods: {
    ...mapActions({
      get_customers: 'customers/get',
      add_customer: 'customers/create',
      get_services: 'services/get',
      add_service: 'services/create',
      store: 'transactions/create',
    }),
    handleConfirmModal() {
      this.confirmModal = !this.confirmModal
    },
    clearAll() {
      this.setItemService = {
        active: false,
        data: {},
        qty: 1,
      }
      this.transaction_field = {
        customer_id: '',
        useReward: false,
        items: [],
        total: 0,
      }
      this.handleRemoveCustomer()
    },
    async handleSubmitTransaction() {
      try {
        const trans_data = { ...this.transaction_field }
        const service_data = []
        if (!trans_data.items.length) {
          this.$toast.error('Transaction Items cannot e empty!')
          return
        }
        this.loading = true
        trans_data.items.forEach(({ data, qty }) => {
          service_data.push({
            service_id: data.id,
            quantity: qty,
          })
        })
        const formdata = {
          customer_id: trans_data.customer_id,
          items: service_data,
          useReward: trans_data.useReward,
          total: this.grand_total,
        }
        const response = await this.store(formdata)
        this.$toast.success('Transaction Success')
        this.handleConfirmModal()
        this.clearAll()
        this.loading = false
      } catch (error) {
        this.loading = false
        console.log(error)
      }
    },

    async resetItems() {
      try {
        this.transaction_field.items = []
      } catch (error) {
        console.log(error)
      }
    },
    handleSelectService() {
      this.setItemService.qty = this.items_qty

      this.addItem(this.setItemService)
    },
    async addItem(data) {
      const { items } = this.transaction_field
      this.items_qty = 1

      await items.unshift(data)
      this.showService(null, false)
    },
    async removeItem(data) {
      const { items } = this.transaction_field

      const filtered = await items.filter((value, index) => index !== data)

      this.transaction_field.items = filtered
    },

    showService(data, isOpen) {
      try {
        if (data) {
          this.search_services = data.name
          this.filtered_services = []
          this.setItemService = {
            active: isOpen,
            data: { ...data },
          }
        } else {
          this.search_services = ''
          this.filtered_services = []
          this.setItemService = {
            active: false,
            data: {},
            qty: 0,
          }
        }
      } catch (error) {
        console.log(error)
      }
    },
    handleNextStep() {
      this.transaction_field.customer_id = this.selected_customer.id
      this.nextStep = !this.nextStep
    },
    handleRemoveCustomer() {
      this.search_customers = ''
      this.selected_customer = {}
      this.nextStep = false
    },
    handleSelectedCustomer(customer) {
      this.search_customers = customer.name
      this.selected_customer = { ...customer }
    },
    async getCustomers() {
      try {
        this.searching_customers = !this.searching_customers
        let params = {
          params: {
            customer: this.search_customers,
          },
        }
        const { data } = await this.get_customers()
        this.searching_customers = !this.searching_customers
      } catch (error) {}
    },
    handleAddCustomerModal() {
      this.addCustomerModal = !this.addCustomerModal
    },
    handleAddServiceModal() {
      this.addServiceModal = !this.addServiceModal
    },
    async getServices() {
      try {
        const { data } = await this.get_services()
      } catch (error) {}
    },
  },
}
</script>

<style></style>
