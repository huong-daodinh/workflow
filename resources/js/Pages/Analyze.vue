<template>
  <AuthenticatedLayout>
    <Head title="Analyze" />
    <div>
      <ul class="flex space-x-2 rtl:space-x-reverse">
        <li>
          <a href="javascript:;" class="text-primary hover:underline">Dashboard</a>
        </li>
        <li class="before:content-['/'] before:mr-2 rtl:before:ml-2">
          <span>Analytics</span>
        </li>
      </ul>
      <div class="pt-5">
        <div class="grid lg:grid-cols-3 gap-6 mb-6"></div>

        <div class="grid sm:grid-cols-3 xl:grid-cols-5 gap-6 mb-6">
          <div class="panel h-full sm:col-span-3 xl:col-span-2">
            <div class="flex items-start justify-between mb-5">
              <h5 class="font-semibold text-lg dark:text-white-light">Tasks status</h5>
            </div>
            <div class="flex flex-col space-y-5">
              <div class="flex items-center">
                <div class="w-9 h-9">
                  <div
                    class="bg-primary/10 text-primary rounded-xl w-9 h-9 flex justify-center items-center dark:bg-primary dark:text-white-light">
                    <icon-chrome class="w-5 h-5" />
                  </div>
                </div>
                <div class="px-3 flex-initial w-full">
                  <div
                    class="w-summary-info flex justify-between font-semibold text-white-dark mb-1">
                    <h6>Pending</h6>
                    <p class="ltr:ml-auto rtl:mr-auto text-xs">65%</p>
                  </div>
                  <div>
                    <div
                      class="w-full rounded-full h-5 p-1 bg-dark-light overflow-hidden shadow-3xl dark:bg-dark-light/10 dark:shadow-none">
                      <div
                        class="bg-gradient-to-r from-[#009ffd] to-[#2a2a72] w-full h-full rounded-full relative before:absolute before:inset-y-0 ltr:before:right-0.5 rtl:before:left-0.5 before:bg-white before:w-2 before:h-2 before:rounded-full before:m-auto"
                        style="width: 65%"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex items-center">
                <div class="w-9 h-9">
                  <div
                    class="bg-danger/10 text-danger rounded-xl w-9 h-9 flex justify-center items-center dark:bg-danger dark:text-white-light">
                    <icon-safari class="w-5 h-5" />
                  </div>
                </div>
                <div class="px-3 flex-initial w-full">
                  <div
                    class="w-summary-info flex justify-between font-semibold text-white-dark mb-1">
                    <h6>In progress</h6>
                    <p class="ltr:ml-auto rtl:mr-auto text-xs">40%</p>
                  </div>
                  <div>
                    <div
                      class="w-full rounded-full h-5 p-1 bg-dark-light overflow-hidden shadow-3xl dark:bg-dark-light/10 dark:shadow-none">
                      <div
                        class="bg-gradient-to-r from-[#a71d31] to-[#3f0d12] w-full h-full rounded-full relative before:absolute before:inset-y-0 ltr:before:right-0.5 rtl:before:left-0.5 before:bg-white before:w-2 before:h-2 before:rounded-full before:m-auto"
                        style="width: 10%"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="flex items-center">
                <div class="w-9 h-9">
                  <div
                    class="bg-warning/10 text-warning rounded-xl w-9 h-9 flex justify-center items-center dark:bg-warning dark:text-white-light">
                    <icon-globe class="w-5 h-5" />
                  </div>
                </div>
                <div class="px-3 flex-initial w-full">
                  <div
                    class="w-summary-info flex justify-between font-semibold text-white-dark mb-1">
                    <h6>Done</h6>
                    <p class="ltr:ml-auto rtl:mr-auto text-xs">25%</p>
                  </div>
                  <div>
                    <div
                      class="w-full rounded-full h-5 p-1 bg-dark-light overflow-hidden shadow-3xl dark:bg-dark-light/10 dark:shadow-none">
                      <div
                        class="bg-gradient-to-r from-[#fe5f75] to-[#fc9842] w-full h-full rounded-full relative before:absolute before:inset-y-0 ltr:before:right-0.5 rtl:before:left-0.5 before:bg-white before:w-2 before:h-2 before:rounded-full before:m-auto"
                        style="width: 25%"></div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="panel h-full p-0">
            <div class="flex p-5">
              <div
                class="shrink-0 bg-success/10 text-success rounded-xl w-11 h-11 flex justify-center items-center dark:bg-success dark:text-white-light">
                <icon-chat-dots class="w-5 h-5" />
              </div>
              <div class="ltr:ml-3 rtl:mr-3 font-semibold">
                <p class="text-xl dark:text-white-light">18.2%</p>
                <h5 class="text-[#506690] text-xs">Progress</h5>
              </div>
            </div>
            <div class="h-40">
              <apexchart
                height="160"
                :options="engagement"
                :series="engagementSeries"
                class="w-full absolute bottom-0 overflow-hidden">
                <!-- loader -->
                <div
                  class="min-h-[160px] grid place-content-center bg-white-light/30 dark:bg-dark dark:bg-opacity-[0.08]">
                  <span
                    class="animate-spin border-2 border-black dark:border-white !border-l-transparent rounded-full w-5 h-5 inline-flex"></span>
                </div>
              </apexchart>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
<script lang="ts" setup>
import { ref, computed } from 'vue';
import apexchart from 'vue3-apexcharts';
import { useAppStore } from '@/stores/index';
import { Head } from '@inertiajs/vue3';

import IconChrome from '@/Components/icon/icon-chrome.vue';
import IconSafari from '@/Components/icon/icon-safari.vue';
import IconGlobe from '@/Components/icon/icon-globe.vue';
import IconChatDots from '@/Components/icon/icon-chat-dots.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
const store = useAppStore();

// statistics
const totalVisit = computed(() => {
  const isDark: boolean = store.theme === 'dark' || store.isDarkMode ? true : false;
  return {
    chart: {
      height: 58,
      type: 'line',
      fontFamily: 'Nunito, sans-serif',
      sparkline: {
        enabled: true
      },
      dropShadow: {
        enabled: true,
        blur: 3,
        color: '#009688',
        opacity: 0.4
      }
    },
    stroke: {
      curve: 'smooth',
      width: 2
    },
    colors: ['#009688'],
    grid: {
      padding: {
        top: 5,
        bottom: 5,
        left: 5,
        right: 5
      }
    },
    tooltip: {
      x: {
        show: false
      },
      y: {
        title: {
          formatter: (val: any) => {
            return '';
          }
        }
      }
    }
  };
});

const totalVisitSeries = ref([
  {
    data: [21, 9, 36, 12, 44, 25, 59, 41, 66, 25]
  }
]);

//paid visit
const paidVisit = computed(() => {
  const isDark: boolean = store.theme === 'dark' || store.isDarkMode ? true : false;
  return {
    chart: {
      height: 58,
      type: 'line',
      fontFamily: 'Nunito, sans-serif',
      sparkline: {
        enabled: true
      },
      dropShadow: {
        enabled: true,
        blur: 3,
        color: '#e2a03f',
        opacity: 0.4
      }
    },
    stroke: {
      curve: 'smooth',
      width: 2
    },
    colors: ['#e2a03f'],
    grid: {
      padding: {
        top: 5,
        bottom: 5,
        left: 5,
        right: 5
      }
    },
    tooltip: {
      x: {
        show: false
      },
      y: {
        title: {
          formatter: (val: any) => {
            return '';
          }
        }
      }
    }
  };
});

const paidVisitSeries = ref([
  {
    data: [22, 19, 30, 47, 32, 44, 34, 55, 41, 69]
  }
]);

// unique visitors
const uniqueVisitor = computed(() => {
  const isDark: boolean = store.theme === 'dark' || store.isDarkMode ? true : false;
  const isRtl = store.rtlClass === 'rtl' ? true : false;
  return {
    chart: {
      height: 360,
      type: 'bar',
      fontFamily: 'Nunito, sans-serif',
      toolbar: {
        show: false
      }
    },
    dataLabels: {
      enabled: false
    },
    stroke: {
      width: 2,
      colors: ['transparent']
    },
    colors: ['#5c1ac3', '#ffbb44'],
    dropShadow: {
      enabled: true,
      blur: 3,
      color: '#515365',
      opacity: 0.4
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: '55%',
        borderRadius: 10
      }
    },
    legend: {
      position: 'bottom',
      horizontalAlign: 'center',
      fontSize: '14px',
      itemMargin: {
        horizontal: 8,
        vertical: 8
      }
    },
    grid: {
      borderColor: isDark ? '#191e3a' : '#e0e6ed',
      padding: {
        left: 20,
        right: 20
      }
    },
    xaxis: {
      categories: [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec'
      ],
      axisBorder: {
        show: true,
        color: isDark ? '#3b3f5c' : '#e0e6ed'
      }
    },
    yaxis: {
      tickAmount: 6,
      opposite: isRtl ? true : false,
      labels: {
        offsetX: isRtl ? -10 : 0
      }
    },
    fill: {
      type: 'gradient',
      gradient: {
        shade: isDark ? 'dark' : 'light',
        type: 'vertical',
        shadeIntensity: 0.3,
        inverseColors: false,
        opacityFrom: 1,
        opacityTo: 0.8,
        stops: [0, 100]
      }
    },
    tooltip: {
      marker: {
        show: true
      },
      y: {
        formatter: (val: any) => {
          return val;
        }
      }
    }
  };
});

const uniqueVisitorSeries = ref([
  {
    name: 'Direct',
    data: [58, 44, 55, 57, 56, 61, 58, 63, 60, 66, 56, 63]
  },
  {
    name: 'Organic',
    data: [91, 76, 85, 101, 98, 87, 105, 91, 114, 94, 66, 70]
  }
]);

// followers
const followers = computed(() => {
  const isDark: boolean = store.theme === 'dark' || store.isDarkMode ? true : false;
  return {
    chart: {
      height: 160,
      type: 'area',
      fontFamily: 'Nunito, sans-serif',
      sparkline: {
        enabled: true
      }
    },
    stroke: {
      curve: 'smooth',
      width: 2
    },
    colors: ['#4361ee'],
    grid: {
      padding: {
        top: 5
      }
    },
    yaxis: {
      show: false
    },
    tooltip: {
      x: {
        show: false
      },
      y: {
        title: {
          formatter: (val: any) => {
            return '';
          }
        }
      }
    },
    option: { fill: null },

    if(isDark: boolean) {
      option: {
        fill: {
          type: 'gradient';
          gradient: {
            type: 'vertical';
            shadeIntensity: 1;
            inverseColors: !1;
            opacityFrom: 0.3;
            opacityTo: 0.05;
            stops: [100, 100];
          }
        }
      }
    }
  };
});

const followersSeries = ref([
  {
    data: [38, 60, 38, 52, 36, 40, 28]
  }
]);

// referral
const referral = computed(() => {
  const isDark: boolean = store.theme === 'dark' || store.isDarkMode ? true : false;
  return {
    chart: {
      height: 160,
      type: 'area',
      fontFamily: 'Nunito, sans-serif',
      sparkline: {
        enabled: true
      }
    },
    stroke: {
      curve: 'smooth',
      width: 2
    },
    colors: ['#e7515a'],
    grid: {
      padding: {
        top: 5
      }
    },
    yaxis: {
      show: false
    },
    tooltip: {
      x: {
        show: false
      },
      y: {
        title: {
          formatter: (val: any) => {
            return '';
          }
        }
      }
    },

    if(isDark: boolean) {
      option: {
        fill: {
          type: 'gradient';
          gradient: {
            type: 'vertical';
            shadeIntensity: 1;
            inverseColors: !1;
            opacityFrom: 0.3;
            opacityTo: 0.05;
            stops: [100, 100];
          }
        }
      }
    }
  };
});

const referralSeries = ref([
  {
    data: [60, 28, 52, 38, 40, 36, 38]
  }
]);

// engagement
const engagement = computed(() => {
  const isDark: boolean = store.theme === 'dark' || store.isDarkMode ? true : false;
  return {
    chart: {
      height: 160,
      type: 'area',
      fontFamily: 'Nunito, sans-serif',
      sparkline: {
        enabled: true
      }
    },
    stroke: {
      curve: 'smooth',
      width: 2
    },
    colors: ['#1abc9c'],
    grid: {
      padding: {
        top: 5
      }
    },
    yaxis: {
      show: false
    },
    tooltip: {
      x: {
        show: false
      },
      y: {
        title: {
          formatter: (val: any) => {
            return '';
          }
        }
      }
    },

    if(isDark: boolean) {
      option: {
        fill: {
          type: 'gradient';
          gradient: {
            type: 'vertical';
            shadeIntensity: 1;
            inverseColors: !1;
            opacityFrom: 0.3;
            opacityTo: 0.05;
            stops: [100, 100];
          }
        }
      }
    }
  };
});

const engagementSeries = ref([
  {
    name: 'Sales',
    data: [28, 50, 36, 60, 38, 52, 38]
  }
]);
</script>
