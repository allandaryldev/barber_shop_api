<template>
  <div class="pt-4 shadow-2xl">
    <Card class="rounded-lg">
      <template #header>
        <div class="px-2">
          <PageTitle page_title="customers">
            <div class="flex justify-end">
              <b-tooltip
                class="d-flex"
                type="is-dark"
                label="Create Customer"
                position="is-left"
              >
                <v-btn color="primary" outlined rounded @click="handleAddModal"
                  >Add Customer</v-btn
                >
              </b-tooltip>
            </div>
          </PageTitle>
        </div>
      </template>

      <template #content>
        <DataTable
          ref="dt"
          :loading="loading"
          :value="customers.data"
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
          :totalRecords="customers_meta.total"
          @page="onPage($event)"
          @sort="onSort($event)"
          :alwaysShow="false"
          :selection.sync="selectedCustomer2"
          @row-select="handleShowModal(selectedCustomer2)" 
          @row-unselect="handleShowModal({})"
          selectionMode="single"
        >
          <template #loading> Loading customers data. Please wait. </template>
          <template #empty> No customers found. </template>
          <!-- <Column :expander="true" headerStyle="width: 3rem" /> -->
          <Column field="id" header="id" sortable>
            <template #filter>
              <InputText
                type="text"
                v-model="filters['id']"
                class="p-column-filter p-inputtext-sm"
                placeholder=" Service Id"
                @keydown.enter="onFilter($event)"
              />
              <!-- <InputNumber
                v-model="filters['id']"
                class="p-column-filter p-inputtext-sm w-10"
                @keydown.enter="onFilter($event)"
              /> -->
            </template>
          </Column>
          <Column field="name" header="Name">
            <template #filter>
              <InputText
                type="text"
                v-model="filters['name']"
                class="p-inputtext-sm"
                placeholder="Customer name"
                @keydown.enter="onFilter($event)"
              />
            </template>
          </Column>
          <Column field="mobile_no" header="Mobile ">
            <template #filter>
              <InputText
                type="phone"
                v-model="filters['mobile_no']"
                class="p-column-filter p-inputtext-sm"
                placeholder="Customer Mobile"
                @keydown.enter="onFilter($event)"
              />
            </template>
          </Column>
          <Column field="group" header="Group ">
            <template #body="slotProps">
              <p class="uppercase">{{ slotProps.data.group }}</p>
            </template>
            <template #filter>
              <!-- @keydown.enter="onFilter($event)" -->
              <Dropdown
                @change="onFilter($event)"
                v-model="filters['group']"
                class="p-column-filter p-inputtext-sm w-full"
                optionValue="value"
                :options="groups"
                optionLabel="name"
                placeholder="Select a Group"
                :showClear="true"
              />
            </template>
          </Column>
          <Column field="points" header="Points " sortable>
            <template #body="slotProps">
              <p class="uppercase">{{ slotProps.data.points }}</p>
            </template>
            <template #filter>
              <InputText
                type="text"
                v-model="filters['points']"
                class="p-column-filter p-inputtext-sm"
                placeholder="Points"
                @keydown.enter="onFilter($event)"
                :showClear="true"
              />
            </template>
          </Column>

          <!-- <Column>
            <template #body="slotProps">
              <div class="">
                <box-icon
                  type="solid"
                  name="edit"
                  animation="tada-hover"
                  color="orange"
                  @click="handleShowModal(slotProps.data)"
                ></box-icon>
                <box-icon type='solid' name='trash' animation="tada-hover" color="red"></box-icon>
              </div>
            </template>
          </Column> -->
        </DataTable>
      </template>
    </Card>

    <ShowCustomer
      :modal="showCustomerModal"
      :close="handleShowModal"
      :get="getCustomers"
      :customer_id="selectedCustomer.id"
    />
    <AddCustomer
      :modal="addCustomerModal"
      :close="handleAddModal"
      :get="getCustomers"
    />

    <ConfirmModal
      :modal="deleteCustomerModal"
      :close="handleDeleteModal"
      :get="getCustomers"
      @confirm="deleteCustomer"
    >
      <div>
        Are you sure you want to delete Customer
        <span class="underline italic">{{ selectedCustomer.name }}</span>
      </div>
    </ConfirmModal>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  data() {
    return {
      loading: false,
      addCustomerModal: false,
      deleteCustomerModal: false,
      showCustomerModal: false,
      groups: [
        {
          name: 'Regular',
          value: 'regular',
        },
        {
          name: 'VIP',
          value: 'VIP',
        },
      ],
      search: {
        name: '',
        mobile_no: '',
        birth_date: '',
        notes: '',
        group: '',
      },
      selectedCustomer: {},
      expandedRows: [],
      lazyParams: {},
      filters: {},
       selectedCustomer2: null,
    }
  },
  computed: {
    ...mapGetters({
      customers: 'customers/customers',
    }),
    customers_meta() {
      return { ...this.customers.meta }
    },
  },
  async created() {
    await this.getCustomers()
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
  methods: {
    ...mapActions({
      fetch: 'customers/get',
      store: 'customers/create',
      update: 'customers/update',
      delete: 'customers/delete',
    }),
    onPage(event) {
      this.lazyParams = event
      this.getCustomers()
      // this.test()
    },
    onSort(event) {
      this.lazyParams = event
      this.getCustomers()
      // this.test()
    },
    onFilter() {
      this.lazyParams.first = 1
      this.getCustomers()
      // this.test()
    },
    test() {
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
      console.log(params)
    },
    async getCustomers() {
      try {
        this.loading = !this.loading
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
        await this.fetch(params)
        this.loading = !this.loading
      } catch (error) {
        console.log(error)
      }
    },
    handleAddModal() {
      this.addCustomerModal = !this.addCustomerModal
    },
    handleDeleteModal(query) {
      this.deleteCustomerModal = !this.deleteCustomerModal
      if (query) {
        this.selectedCustomer = query
      } else {
        this.selectedCustomer = {}
      }
    },
    handleShowModal(query) {
      this.showCustomerModal = !this.showCustomerModal
      if (query) {
        this.selectedCustomer = query
      } else {
        this.selectedCustomer = {}
      }
    },
    deleteCustomer() {
      try {
        this.delete(this.selectedCustomer.id)
        this.handleDeleteModal()
      } catch (error) {
        console.log(error)
      }
    },
  },
}
</script>

<style></style>
