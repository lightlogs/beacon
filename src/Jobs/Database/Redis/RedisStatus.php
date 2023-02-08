<?php

namespace Lightlogs\Beacon\Jobs\Database\Redis;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Lightlogs\Beacon\Collector;
use Lightlogs\Beacon\Generator;
use Lightlogs\Beacon\ExampleMetric\GenericMixedMetric;
use Lightlogs\Beacon\Jobs\Database\Traits\StatusVariables; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Bus\Dispatchable;

class RedisStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels, StatusVariables;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    public function __construct()
    {
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {

        $redis = \Redis::connection();
        $variables = $redis->command('info');

        $metric = new GenericMixedMetric();
        $metric->name = 'redis.status';
        $metric->string_metric5 = $variables['Client']['connected_clients']; 
        $metric->string_metric6 = $variables['Memory']['used_memory']; 
        $metric->string_metric7 = $variables['Memory']['used_memory_peak_perc']; 
        $metric->string_metric8 = $variables['Memory']['maxmemory_policy']; 
        $metric->string_metric6 = $variables['Memory']['maxmemory_human']; 

        $collector = new Collector();
        $collector->create($metric)
            ->batch();

    }


}

/*
=> [
     "Server" => [
       "redis_version" => "4.0.9",
       "redis_git_sha1" => "00000000",
       "redis_git_dirty" => "0",
       "redis_build_id" => "9435c3c2879311f3",
       "redis_mode" => "standalone",
       "os" => "Linux 5.4.0-89-generic x86_64",
       "arch_bits" => "64",
       "multiplexing_api" => "epoll",
       "atomicvar_api" => "atomic-builtin",
       "gcc_version" => "7.4.0",
       "process_id" => "1021",
       "run_id" => "dfbd53157a305523c72d9585d6fb7ae1528338b6",
       "tcp_port" => "6379",
       "uptime_in_seconds" => "1138268",
       "uptime_in_days" => "13",
       "hz" => "10",
       "lru_clock" => "9203847",
       "executable" => "/usr/bin/redis-server",
       "config_file" => "/etc/redis/redis.conf",
     ],
     "Clients" => [
       "connected_clients" => "5",
       "client_longest_output_list" => "0",
       "client_biggest_input_buf" => "0",
       "blocked_clients" => "0",
     ],
     "Memory" => [
       "used_memory" => "2696536",
       "used_memory_human" => "2.57M",
       "used_memory_rss" => "4898816",
       "used_memory_rss_human" => "4.67M",
       "used_memory_peak" => "3842976",
       "used_memory_peak_human" => "3.66M",
       "used_memory_peak_perc" => "70.17%",
       "used_memory_overhead" => "941774",
       "used_memory_startup" => "782504",
       "used_memory_dataset" => "1754762",
       "used_memory_dataset_perc" => "91.68%",
       "total_system_memory" => "8219828224",
       "total_system_memory_human" => "7.66G",
       "used_memory_lua" => "46080",
       "used_memory_lua_human" => "45.00K",
       "maxmemory" => "0",
       "maxmemory_human" => "0B",
       "maxmemory_policy" => "noeviction",
       "mem_fragmentation_ratio" => "1.82",
       "mem_allocator" => "jemalloc-3.6.0",
       "active_defrag_running" => "0",
       "lazyfree_pending_objects" => "0",
     ],
     "Persistence" => [
       "loading" => "0",
       "rdb_changes_since_last_save" => "0",
       "rdb_bgsave_in_progress" => "0",
       "rdb_last_save_time" => "1636592402",
       "rdb_last_bgsave_status" => "ok",
       "rdb_last_bgsave_time_sec" => "0",
       "rdb_current_bgsave_time_sec" => "-1",
       "rdb_last_cow_size" => "479232",
       "aof_enabled" => "0",
       "aof_rewrite_in_progress" => "0",
       "aof_rewrite_scheduled" => "0",
       "aof_last_rewrite_time_sec" => "-1",
       "aof_current_rewrite_time_sec" => "-1",
       "aof_last_bgrewrite_status" => "ok",
       "aof_last_write_status" => "ok",
       "aof_last_cow_size" => "0",
     ],
     "Stats" => [
       "total_connections_received" => "1337",
       "total_commands_processed" => "5495235",
       "instantaneous_ops_per_sec" => "0",
       "total_net_input_bytes" => "2690431076",
       "total_net_output_bytes" => "19048280620",
       "instantaneous_input_kbps" => "0.01",
       "instantaneous_output_kbps" => "0.00",
       "rejected_connections" => "0",
       "sync_full" => "0",
       "sync_partial_ok" => "0",
       "sync_partial_err" => "0",
       "expired_keys" => "697",
       "expired_stale_perc" => "0.00",
       "expired_time_cap_reached_count" => "0",
       "evicted_keys" => "0",
       "keyspace_hits" => "1685642",
       "keyspace_misses" => "759768",
       "pubsub_channels" => "0",
       "pubsub_patterns" => "0",
       "latest_fork_usec" => "293",
       "migrate_cached_sockets" => "0",
       "slave_expires_tracked_keys" => "0",
       "active_defrag_hits" => "0",
       "active_defrag_misses" => "0",
       "active_defrag_key_hits" => "0",
       "active_defrag_key_misses" => "0",
     ],
     "Replication" => [
       "role" => "master",
       "connected_slaves" => "0",
       "master_replid" => "6a32d4163fd4a8ce0ccbd58c031292eb6a59a490",
       "master_replid2" => "0000000000000000000000000000000000000000",
       "master_repl_offset" => "0",
       "second_repl_offset" => "-1",
       "repl_backlog_active" => "0",
       "repl_backlog_size" => "1048576",
       "repl_backlog_first_byte_offset" => "0",
       "repl_backlog_histlen" => "0",
     ],
     "CPU" => [
       "used_cpu_sys" => "1630.95",
       "used_cpu_user" => "716.41",
       "used_cpu_sys_children" => "0.79",
       "used_cpu_user_children" => "4.78",
     ],
     "Cluster" => [
       "cluster_enabled" => "0",
     ],
     "Keyspace" => [
       "db0" => [
         "keys" => "1",
         "expires" => "0",
         "avg_ttl" => "0",
       ],
       "db1" => [
         "keys" => "847",
         "expires" => "0",
         "avg_ttl" => "0",
       ],
     ],
   ]
 */