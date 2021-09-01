<template>
  <div class="pt-4 shadow-2xl">
    <Card class="rounded-lg">
      <template #header>
        <div class="px-2 py-4">
          <PageTitle page_title="Services">
            <div class="flex justify-end">
              <b-tooltip
                class="d-flex"
                type="is-dark"
                label="Create Service"
                position="is-left"
              >
                <v-btn color="primary" outlined rounded @click="handleAddModal"
                  >Add Service</v-btn
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
          :value="services.data"
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
          :totalRecords="services_meta.total"
          @page="onPage($event)"
          @sort="onSort($event)"
          :alwaysShow="false"
          :selection.sync="selectedService"
          @row-select="handleShowModal(selectedService)" 
          @row-unselect="handleShowModal({})"
          selectionMode="single"
        >
          <!-- <template #header>
            <div class="table-header">Serv</div>
          </template> -->
          <template #loading> Loading Services data. Please wait. </template>
          <template #empty> No Services found. </template>
          <Column :expander="true" headerStyle="width: 3rem" />
          <Column field="id" header="id" sortable>
            <template #filter>
              <InputText
                type="text"
                v-model="filters['id']"
                class="p-column-filter p-inputtext-sm"
                placeholder=" Service Id"
                @keydown.enter="onFilter($event)"
              />
            </template>
          </Column>
          <Column field="name" header="Name">
            <template #filter>
              <InputText
                type="text"
                v-model="filters['name']"
                class="p-column-filter p-inputtext-sm"
                placeholder=" Service Name"
                @keydown.enter="onFilter($event)"
              />
            </template>
          </Column>
          <Column field="description" header="Description">
            <template #body="slotProps">
              <p class="truncate">{{ slotProps.data.description }}</p>
            </template>
          </Column>
          <Column field="value" header="Value">
            <template #body="slotProps">
              {{ $moneyFormat.format(slotProps.data.value) }}
            </template>
            <template #filter>
              <InputText
                type="text"
                v-model="filters['value']"
                class="p-column-filter p-inputtext-sm"
                placeholder=" Service Value"
                @keydown.enter="onFilter($event)"
              />
            </template>
          </Column>

          <template #expansion="slotProps">
            <div>
              {{ slotProps.data.description }}
            </div>
          </template>
        </DataTable>
      </template>
    </Card>
    <ServiceShow
      :modal="showServiceModal"
      :close="handleShowModal"
      :get="getServices"
      :service_id="selected.id"
    />
    <ServiceAdd
      :modal="addServiceModal"
      :close="handleAddModal"
      :get="getServices"
    />
    <ConfirmModal
      :modal="deleteServiceModal"
      :close="handleDeleteModal"
      :get="getServices"
      @confirm="deleteService(selected.id)"
    >
      <div>
        Are you sure you want to delete Service:
        <span class="underline italic">{{ selected.name }}</span>
      </div>
    </ConfirmModal>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
export default {
  data() {
    return {
      loading: true,
      addServiceModal: false,
      deleteServiceModal: false,
      showServiceModal: false,
      selected: {},
      expandedRows: [],
      lazyParams: {},
      filters: {},
      selectedService:{}
    }
  },
  async created() {
    await this.getServices()
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
      services: 'services/services',
    }),
    services_meta() {
      return { ...this.services.meta }
    },
  },
  methods: {
    ...mapActions({
      get: 'services/get',
      delete: 'services/delete',
    }),

    onPage(event) {
      this.lazyParams = event
      this.getServices()
      // this.test()
    },
    onSort(event) {
      this.lazyParams = event
      this.getServices()
      // this.test()
    },
    onFilter() {
      this.lazyParams.first = 1
      this.getServices()
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
    async getServices() {
      try {
        this.loading = true
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
        await this.get(params)
        this.loading = false
      } catch (error) {}
    },
    handleAddModal() {
      this.addServiceModal = !this.addServiceModal
    },
    handleShowModal(value) {
      this.showServiceModal = !this.showServiceModal
      if (value) {
        this.selected = value
      } else {
        this.selected = {}
      }
    },
    handleDeleteModal(value) {
      this.deleteServiceModal = !this.deleteServiceModal
      this.selected = value
    },
    async deleteService(id) {
      try {
        this.loading = true
        this.handleDeleteModal({})
        await this.delete(id)
        this.loading = false
      } catch (error) {}
    },
  },
}
</script>

<style></style>
