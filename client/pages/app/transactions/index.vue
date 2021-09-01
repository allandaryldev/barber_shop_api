<template>
  <div class="pt-4 shadow-2xl">
    <Card class="rounded-lg">
      <template #header>
        <Toolbar class="p-mb-4">
          <template #left>
        
          </template>

          <template #right> 

                <b-tooltip
              class="d-flex"
              type="is-dark"
              label="Create Transactions"
              position="is-left"
            >
              <NuxtLink
                :to="{
                  name: 'app-transactions-create',
                }"
              >
                <v-btn color="primary" outlined rounded>Add Transactions</v-btn>
              </NuxtLink>
            </b-tooltip>
          </template>
        </Toolbar>
      </template>

      <template #content>
        <DataTable
          ref="dt"
          :loading="loading"
          :value="transactions.data"
          dataKey="id"
          class="p-datatable-customers p-datatable-striped p-datatable-sm"
          :rows="10"
          :filters="filters"
          :expandedRows.sync="expandedRows"
          :rowsPerPageOptions="[10, 20, 50]"
          rowHover
          paginator
          currentPageReportTemplate="Showing {first} to {last} of {totalRecords}"
          paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
          lazy
          :totalRecords="transactions_meta.total"
          @page="onPage($event)"
          @sort="onSort($event)"
          :alwaysShow="false"
        >
          <template #header>
            <div class="table-header">Transactions</div>
          </template>
          <template #loading> Loading customers data. Please wait. </template>
          <template #empty> No customers found. </template>
          <Column :expander="true" headerStyle="width: 3rem" />
          <Column field="id" header="id" sortable>
            <template #body="slotProps" class="">
              {{ slotProps.data.id }}
            </template>
            <template #filter>
              <InputText
                type="text"
                v-model="filters['id']"
                class="p-column-filter p-inputtext-sm"
                placeholder="Transaction ID"
                @keydown.enter="onFilter($event)"
              />
            </template>
          </Column>
          <Column field="customer.name" header="Customer Name">
            <template #body="slotProps">
              {{ slotProps.data.customer.name }}
            </template>
            <template #filter>
              <InputText
                type="text"
                v-model="filters['name']"
                class="p-column-filter p-inputtext-sm"
                placeholder=" Customer Name"
                @keydown.enter="onFilter($event)"
              />
            </template>
          </Column>
          <Column field="total" header="Total" sortable>
            <template #body="slotProps">
              {{ $moneyFormat.format(slotProps.data.total) }}
            </template>
            <template #filter>
              <InputText
                type="text"
                v-model="filters['total']"
                class="p-column-filter p-inputtext-sm"
                placeholder=" Total"
                @keydown.enter="onFilter($event)"
              /> </template
          ></Column>
          <Column field="created_at" header="Transact At"></Column>

          <template #expansion="slotProps">
            <div class="grid grid-cols-3 gap-2">
              <div v-for="item in slotProps.data.items" :key="item.id">
                <Card class="px-2 py-4 rounded-lg">
                  <template #header> Service </template>
                  <template #content>
                    <v-list shaped class="p-0 m-0">
                      <v-list-item class="p-0 m-0">
                        <v-list-item-content class="p-0 m-0">
                          <v-list-item-title
                            class="capitalize font-semibold"
                            v-text="item.service.name"
                          ></v-list-item-title>
                          <v-list-item-subtitle>
                            <div class="font-semibold">
                              Cost:
                              <span class="font-bold">{{
                                $moneyFormat.format(item.service.value)
                              }}</span>
                            </div>
                            <div class="font-semibold">
                              Quantity:
                              <span class="font-bold">{{ item.quantity }}</span>
                            </div>
                            <div class="font-semibold">
                              Total Cost:
                              <span class="font-bold">{{
                                $moneyFormat.format(
                                  item.service.value * item.quantity
                                )
                              }}</span>
                            </div>
                          </v-list-item-subtitle>
                        </v-list-item-content>
                      </v-list-item>
                    </v-list>
                  </template>
                </Card>
              </div>
            </div>
          </template>
        </DataTable>
      </template>
    </Card>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  data() {
    return {
      loading: false,
      filters: {},
      modal: {
        add: false,
        edit: false,
        delete: false,
      },
      expandedRows: [],
      lazyParams: {},
    }
  },
  async created() {
    await Promise.all([this.getTransactions()])
  },
  mounted() {
    this.lazyParams = {
      first: 1,
      rows: this.$refs.dt.rows,
      sortField: null,
      sortOrder: null,
      filters: this.filters,
    }
  },
  computed: {
    ...mapGetters({
      transactions: 'transactions/transactions',
    }),
    transactions_meta() {
      return { ...this.transactions.meta }
    },
  },
  methods: {
    ...mapActions({
      get: 'transactions/get',
    }),
    onPage(event) {
      this.lazyParams = event
      this.getTransactions()
      // this.test()
    },
    onSort(event) {
      this.lazyParams = event
      this.getTransactions()
      // this.test()
    },
    onFilter() {
      this.lazyParams.first = 1
      this.getTransactions()
      // this.test()
    },
    test() {
      const params = {
        params: {
          ...this.lazyParams,
          page: this.lazyParams.page ? this.lazyParams.page : 1,
          order_by: this.lazyParams.sortField,
          direction: this.lazyParams.sortOrder === 1 ? 'asc' : 'desc',
          ...this.filters,
        },
      }
      console.log(params)
    },
    async getTransactions() {
      try {
        const params = {
          params: {
            rows: this.lazyParams.rows,
            order_by: this.lazyParams.sortField,
            direction: this.lazyParams.sortOrder === 1 ? 'asc' : 'desc',
            // page: this.lazyParams.page + 1,
            page: this.lazyParams.page ? this.lazyParams.page + 1 : 1,
            ...this.filters,
          },
        }
        this.loading = true
        await this.get(params)
        this.loading = false
      } catch (error) {
        console.log(error)
      }
    },
  },
}
</script>

<style></style>
