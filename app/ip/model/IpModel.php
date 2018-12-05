<?php
// +----------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013-2017 http://www.thinkcmf.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 老猫 <thinkcmf@126.com>
// +----------------------------------------------------------------------
namespace app\ip\model;

use think\Db;
use think\Model;

class IpModel extends Model
{
	
       public function insterip($da)
    {
        $uQuery = Db::name("ip");
			$d   = [
                'ip' => $da["ip"],
                'country_id'   => $da["country_id"],
                'area_id'   => $da["area_id"],
                'region_id'   => $da["region_id"],
                'city_id'   => $da["city_id"],
                'county_id'   => $da["county_id"],
                'isp_id'   => $da["isp_id"],
            ];
			$uQuery->insert($d);
		if($da['country_id']!=-1){
        $uQuery = Db::name("ip_country");
        $result    = $uQuery->where('country_id', $da['country_id'])->find();
		if (empty($result)) {
			$d   = [
                'country_id'   => $da["country_id"],
                'country'   => $da["country"],
            ];
			$uQuery->insert($d);
        }}
		if($da['area_id']!=-1){
        $uQuery = Db::name("ip_area");
        $result    = $uQuery->where('area_id', $da['area_id'])->find();
		if (empty($result)) {
			$d   = [
                'country_id'   => $da["country_id"],
                'area_id'   => $da["area_id"],
                'area'   => $da["area"],
            ];
			$uQuery->insert($d);
        }}
		if($da['region_id']!=-1){
        $uQuery = Db::name("ip_region");
        $result    = $uQuery->where('region_id', $da['region_id'])->find();
		if (empty($result)) {
			$d   = [
                'area_id'   => $da["area_id"],
                'region_id'   => $da["region_id"],
                'region'   => $da["region"],
            ];
			$uQuery->insert($d);
        }}
		if($da['city_id']!=-1){
        $uQuery = Db::name("ip_city");
        $result    = $uQuery->where('city_id', $da['city_id'])->find();
		if (empty($result)) {
			$d   = [
                'region_id'   => $da["region_id"],
                'city_id'   => $da["city_id"],
                'city'   => $da["city"],
            ];
			$uQuery->insert($d);
        }}
		if($da["county_id"]!=-1){
        $uQuery = Db::name("ip_county");
        $result    = $uQuery->where('county_id', $da['county_id'])->find();
		if (empty($result)) {
			$d   = [
                'city_id'   => $da["city_id"],
                'county_id'   => $da["county_id"],
                'county'   => $da["county"],
            ];
			$uQuery->insert($d);
        }}
		if($da["isp_id"]!=-1){
        $uQuery = Db::name("ip_isp");
        $result    = $uQuery->where('isp_id', $da['isp_id'])->find();
		if (empty($result)) {
			$d   = [
                'isp_id'   => $da["isp_id"],
                'isp'   => $da["isp"],
            ];
			$uQuery->insert($d);
        }
		}
    }
	
	
	
       public function Addipinfo($da)
    {
        $uQuery = Db::name("ip");
		if($da['region_id']!=-1){
		
        $result    = $uQuery->where('id', $da['id'])->find();
		if (empty($result)) {
			$d   = [
                'ip' => $da["ip"],
                'country_id'   => $da["country_id"],
                'area_id'   => $da["area_id"],
                'region_id'   => $da["region_id"],
                'city_id'   => $da["city_id"],
                'county_id'   => $da["county_id"],
                'isp_id'   => $da["isp_id"],
            ];
			$uQuery->insert($d);
            //$mId = $mQuery->insertGetId($da);
            return 0;
        }else{
			$d   = [
                'area_id'   => $da["area_id"],
                'region_id'   => $da["region_id"],
                'city_id'   => $da["city_id"],
                'county_id'   => $da["county_id"],
                'isp_id'   => $da["isp_id"],
            ];
			$uQuery->where('id', $da['id']) ->update($d);
		}
		}
			$d   = [
                'sch_sta'   => 1
            ];
			$uQuery->where('id', $da['id']) ->update($d);
		
		
		if($da['country_id']!=-1){
        $uQuery = Db::name("ip_country");
        $result    = $uQuery->where('country_id', $da['country_id'])->find();
		if (empty($result)) {
			$d   = [
                'country_id'   => $da["country_id"],
                'country'   => $da["country"],
            ];
			$uQuery->insert($d);
        }}
		if($da['area_id']!=-1){
        $uQuery = Db::name("ip_area");
        $result    = $uQuery->where('area_id', $da['area_id'])->find();
		if (empty($result)) {
			$d   = [
                'country_id'   => $da["country_id"],
                'area_id'   => $da["area_id"],
                'area'   => $da["area"],
            ];
			$uQuery->insert($d);
        }}
		if($da['region_id']!=-1){
        $uQuery = Db::name("ip_region");
        $result    = $uQuery->where('region_id', $da['region_id'])->find();
		if (empty($result)) {
			$d   = [
                'area_id'   => $da["area_id"],
                'region_id'   => $da["region_id"],
                'region'   => $da["region"],
            ];
			$uQuery->insert($d);
        }}
		if($da['city_id']!=-1){
        $uQuery = Db::name("ip_city");
        $result    = $uQuery->where('city_id', $da['city_id'])->find();
		if (empty($result)) {
			$d   = [
                'region_id'   => $da["region_id"],
                'city_id'   => $da["city_id"],
                'city'   => $da["city"],
            ];
			$uQuery->insert($d);
        }}
		if($da["county_id"]!=0){
        $uQuery = Db::name("ip_county");
        $result    = $uQuery->where('county_id', $da['county_id'])->find();
		if (empty($result)) {
			$d   = [
                'city_id'   => $da["city_id"],
                'county_id'   => $da["county_id"],
                'county'   => $da["county"],
            ];
			$uQuery->insert($d);
        }}
		if($da["isp_id"]!=0){
        $uQuery = Db::name("ip_isp");
        $result    = $uQuery->where('isp_id', $da['isp_id'])->find();
		if (empty($result)) {
			$d   = [
                'isp_id'   => $da["isp_id"],
                'isp'   => $da["isp"],
            ];
			$uQuery->insert($d);
        }
		}
    }
	
	
}